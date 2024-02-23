<!DOCTYPE html>
<html lang="en">
<?php include '../../utils/head.php'; ?>
<?php include '../../utils/databaseHandler.php'?>
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

foreach($ballots as $ballot) {
	if($ballot['createBy'] == $_SESSION['id']) {
		$organize[] = $ballot;
	}
	foreach($ballot['voterList'] as $key => $value) {
		if($key == $_SESSION['email']) {
			$voter[] = $ballot;
		}
	}
}

usort ($organize, function($a, $b) {
	$statusOrder = ['Running' => 1, 'Not started' => 2, 'Other' => 3];

	$statusA = $statusOrder[$a['status']] ?? $statusOrder['Other'];
    $statusB = $statusOrder[$b['status']] ?? $statusOrder['Other'];

	if ($statusA !== $statusB) {
        return $statusA <=> $statusB;
    }

	return $a['startDate'] <=> $b['startDate'];
})

?>

<body class="loggedin">
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
		<a href="../create_ballot/create_ballot.php">Create Ballot</a>
	</div>
	<div class="organize">
		<?php 
		require_once './components/organize.php';
		foreach ($organize as $ballot) {
			echo OrganizeBallot($ballot);
		}
		?>
	</div>
</body>

</html>
<?php include '../../utils/foot.php' ?>