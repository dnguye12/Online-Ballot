<!DOCTYPE html>
<html lang="en">

<head>
	<?php include '../../utils/head.php'; ?>
</head>
<?php include '../../utils/databaseHandler.php' ?>
<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../../index.php');
	exit;
}

$filePath = '../../database/ballots.json';
$ballots = loadDataFromFile($filePath);

$organize = array();
$voter = array();

foreach ($ballots as $ballot) {
	if ($ballot['createBy'] == $_SESSION['id']) {
		$organize[] = $ballot;
	}
	foreach ($ballot['voterList'] as $key => $value) {
		if ($key == $_SESSION['email']) {
			$voter[] = $ballot;
		}
	}
}

usort($organize, function ($a, $b) {
	$statusOrder = ['Running' => 1, 'Not started' => 2, 'Other' => 3];

	$statusA = $statusOrder[$a['status']] ?? $statusOrder['Other'];
	$statusB = $statusOrder[$b['status']] ?? $statusOrder['Other'];

	if ($statusA !== $statusB) {
		return $statusA <=> $statusB;
	}

	return $a['startDate'] <=> $b['startDate'];
});

usort($voter, function ($a, $b) {
	$statusOrder = ['Running' => 1, 'Not started' => 2, 'Other' => 3];

	$statusA = $statusOrder[$a['status']] ?? $statusOrder['Other'];
	$statusB = $statusOrder[$b['status']] ?? $statusOrder['Other'];

	if ($statusA !== $statusB) {
		return $statusA <=> $statusB;
	}

	return $a['startDate'] <=> $b['startDate'];
});

?>

<body class="home">
	<div id="homeMain">
		<nav class="navtop">
			<div>
				<h1>Website Title</h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="utils/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Home Page</h2>
			<p>Welcome back, <?= htmlspecialchars($_SESSION['name'], ENT_QUOTES) ?>!</p>
		</div>
		<div class="action">
			<a href="../create_ballot/create_ballot.php">Create Election</a>
		</div>
		<div class="organize">
			<?php
			require_once './components/organize.php';
			foreach ($organize as $ballot) {
				echo OrganizeBallot($ballot);
			}
			?>
		</div>
		<h3>You can vote for</h3>
		<div class="voter">
			<?php
			require_once './components/voter.php';
			foreach ($voter as $ballot) {
				echo VoterBallot($ballot);
			}
			?>
		</div>
	</div>
	<div id="voteFormContainer"></div>
	<div id="voteMessage"></div>
</body>

</html>
<?php include '../../utils/foot.php' ?>
<script>
	$(document).ready(function() {
		$('.start-vote').on('click', function() {
			let ballot = $(this).data('ballot');
			$.ajax({
				url: './components/voteForm.php',
				type: 'POST',
				data: {"ballot" : ballot},
			}).done(function(e) {
				$('#homeMain').hide();
				$('#voteFormContainer').html(e);
			})
		})
	})
	
	$(document).ready(function() {
		$('.home').on('submit', '#voteForm', function(e) {
			e.preventDefault();
			let formData = $(this).serialize();
			let res = formData.split('&').reduce(function(acc, curr) {
				let parts = curr.split('=');
				if(!acc.questions) {
					acc.questions = [];
				}
				if(parts[0] === 'ballotId' || parts[0] === 'email') {
					acc[decodeURIComponent(parts[0])] = decodeURIComponent(parts[1]);
				}else {
					acc.questions.push(decodeURIComponent(parts[1]));
				}
				return acc;
			}, {})

			$.ajax({
				url: './utils/saveVote.php',
				type: 'POST',
				data: {"res": res},
			}).done(function(e) {
				$('#homeMain').hide();
				$('#voteMessage').html(e);
			})
		})
	})
</script>