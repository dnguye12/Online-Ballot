<!DOCTYPE html>
<html lang="en">

<head>
	<?php include '../../utils/head.php'; ?>
	<link rel="stylesheet" href="./utils/home.css">
</head>
<?php include '../../utils/databaseHandler.php' ?>
<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../../index.php');
	exit;
}

// Charge les scrutins
$filePath = '../../database/ballots.json';
$ballots = loadDataFromFile($filePath);

// Initialise des tableaux de scrutins
$organize = array(); // la tableau de scrutins organisés par l'utilisateur
$voter = array(); // la tableau de scrutins où l'utilisateur est électeur

// Trie les scrutins en fonction de si l'utilisateur est organisateur ou électeur
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

// Trie les scrutins organisés par statut et date de début
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

<body class="home pb-5">
	<header>
		<nav class="navbar shadow-sm">
			<div class="container">
				<a href="." class="logo">OnlineBallot</a>
				<a href="utils/logout.php" class="logoutbtn nav-link px-sm-3 py-sm-2 me-sm-2 p-2 shadow-sm"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
			</div>
		</nav>
	</header>
	<div id="homeMain" class="py-5 px-sm-0 px-3">
		<div class="container welcome shadow rounded px-sm-4 px-3 py-3 mb-4">
			<div class="content">
				<h2>Home Page</h2>
				<p class="mb-3">Welcome back, <?= htmlspecialchars($_SESSION['name'], ENT_QUOTES) ?>!</p>
			</div>
			<div class="action">
				<a href="../create_ballot/create_ballot.php" class="createbtn px-sm-3 py-sm-2 p-2 btn rounded shadow-sm me-2"><i class="fa-solid fa-plus me-1"></i>Create A New Ballot</a>
				<a href="utils/logout.php" class="logoutbtn px-sm-3 py-sm-2 p-2 btn rounded shadow-sm"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
			</div>
		</div>

		<!-- Affiche les scrutins organisés par l'utilisateur -->
		<div class="organize container shadow rounded px-0 my-5">
			<div class="container-header px-sm-4 px-3 py-3">
				<h3 class="mb-0">Your ballots</h3>
			</div>
			<div class="content row px-sm-4 py-sm-4 px-3 py-3 g-3">
				<?php
				// Inclut et affiche les composants des scrutins organisés
				require_once './components/organize.php';
				if (count($organize) > 0) {
					foreach ($organize as $ballot) {
						echo OrganizeBallot($ballot);
					}
				} else {
					echo "<p class='mt-3 mb-0'>You haven't created any ballots.</p>";
				}
				?>
			</div>
		</div>

		<div class="voter container shadow rounded px-0">
			<div class="container-header px-sm-4 px-3 py-3">
				<h3 class="mb-0">You are a voter of</h3>
			</div>
			<div class="content row px-sm-4 py-sm-4 px-3 py-3 g-3">
				<?php
				// Inclut et affiche les composants des scrutins où l'utilisateur est électeur
				require_once './components/voter.php';
				if (count($voter) > 0) {
					foreach ($voter as $ballot) {
						echo VoterBallot($ballot);
					}
				} else {
					echo "<p class='mt-3 mb-0'>You are not elector for any ballot.</p>";
				}
				?>
			</div>
		</div>
	</div>
	<!-- Zones pour afficher les formulaires et messages supplémentaires -->
	<div id="ballotQuestions"></div>
	<div id="transferVoteFormContainer"></div>
	<div id="organizeMessage"></div>

	<div id="voteFormContainer"></div>
	<div id="voteMessage"></div>

	<div id="statContainer"></div>
	<footer class="py-3">
		<p class="mb-0 container ">Copyright @ <?php echo date('Y'); ?> <b>OnlineBallot</b>. All Rights Reserved.</p>
	</footer>
</body>

</html>
<?php include '../../utils/foot.php' ?>
<script src="../../utils/alertHandler.js"></script>
<script>
	// Gestion de fermeture d'un scrutin
	$(document).ready(function() {
		$('.close-ballot').on('click', function() {
			let ballot = $(this).data('ballot');
			// Affiche une alerte de confirmation avant de fermer le scrutin
			AlertWarning("Close ballot confirmation", "Are you sure you want to proceed?", function() {
				// Effectue une requête AJAX pour fermer le scrutin
				$.ajax({
					url: './utils/closeBallot.php',
					type: 'POST',
					data: {
						"ballot": ballot
					},
				}).done(function(e) {
					let helper = JSON.parse(e);
					AlertSuccess('Close ballot success', `You have successfully closed ${helper.title}`, function() {
						location.reload();
					});
				})
			});
		})
	})

	// Gestion de suppression d'un scrutin
	$(document).ready(function() {
		$('.delete-ballot').on('click', function() {
			let ballot = $(this).data('ballot');
			// Affiche une alerte de confirmation avant de supprimer le scrutin
			AlertWarning("Delete confirmation", "Are you sure you want to proceed?", function() {
				// Effectue une requête AJAX pour supprimer le scrutin
				$.ajax({
					url: './utils/deleteBallot.php',
					type: 'POST',
					data: {
						"ballot": ballot
					},
				}).done(function(e) {
					let helper = JSON.parse(e);
					AlertSuccess('Delete success', `You have successfully deleted ${helper.title}`, function() {
						$('#organize_' + helper.id).remove();
						$('#voter_' + helper.id).remove();
					});
				})
			});
		})
	})

	//Voir un scrutin en détails
	$(document).ready(function() {
		$('.ballot-question').on('click', function() {
			let ballot = $(this).data('ballot');
			$.ajax({
				url: './components/questions.php',
				type: 'POST',
				data: {
					"ballot": ballot
				},
			}).done(function(e) {
				$('#homeMain').hide();
				$('#ballotQuestions').html(e);
			})
		})
	})

	//Ouvrir l'interface pour transférer le vote (uniquement pour les organisateurs)
	$(document).ready(function() {
		$('.transfer-vote').on('click', function() {
			let ballot = $(this).data('ballot');
			$.ajax({
				url: './components/transferVoteForm.php',
				type: 'POST',
				data: {
					"ballot": ballot
				},
			}).done(function(e) {
				$('#homeMain').hide();
				$('#transferVoteFormContainer').html(e);
			})
		})
	})

	// Ouvrir la page de statistiques pour un scrutin
	$(document).ready(function() {
		$('.stat').on('click', function() {
			let ballot = $(this).data('ballot');
			$.ajax({
				url: './components/stat.php',
				type: 'POST',
				data: {
					"ballot": ballot
				},
			}).done(function(e) {
				$('#homeMain').hide();
				$('#statContainer').html(e);
			})
		})
	})

	// Gestion de la soumission du formulaire de transfert de vote
	$(document).ready(function() {
		$('.home').on('submit', '#transferForm', function(e) {
			e.preventDefault();
			let formData = $(this).serialize();
			let res = formData.split('&').reduce(function(acc, curr) {
				let parts = curr.split('=');
				if (!acc.transfer) {
					acc.transfer = [];
				}
				if (parts[0] === 'ballotId') {
					acc[decodeURIComponent(parts[0])] = decodeURIComponent(parts[1]);
				} else {
					acc.transfer.push(decodeURIComponent(parts[1]));
				}
				return acc;
			}, {})

			// Envoie les données au serveur via AJAX
			$.ajax({
				url: './utils/transferVote.php',
				type: 'POST',
				data: {
					"res": res
				},
			}).done(function(e) {
				$('#homeMain').hide();
				$('#organizeMessage').html(e);
			})
		})
	})
</script>
<script>
	// Ouvrir la page de vote d'un bulletin de vote
	$(document).ready(function() {
		$('.start-vote').on('click', function() {
			let ballot = $(this).data('ballot');
			$.ajax({
				url: './components/voteForm.php',
				type: 'POST',
				data: {
					"ballot": ballot
				},
			}).done(function(e) {
				$('#homeMain').hide();
				$('#voteFormContainer').html(e);
			})
		})
	});


	// Gestion de la soumission du formulaire de vote
	$(document).ready(function() {
		$('.home').on('submit', '#voteForm', function(e) {
			e.preventDefault();
			let formData = $(this).serialize();
			// Demande de confirmation avant de soumettre le vote
			AlertWarning("Confirm confirmation", "Are you sure you want to submit your vote?", function() {
				// Transforme les données du formulaire en un objet structuré
				let res = formData.split('&').reduce(function(acc, curr) {
					let parts = curr.split('=');

					if (!acc.questions) {
						acc.questions = [];
					}
					if (parts[0] === 'ballotId' || parts[0] === 'email') {
						acc[decodeURIComponent(parts[0])] = decodeURIComponent(parts[1]);
					} else {
						acc.questions.push(decodeURIComponent(parts[1]));
					}
					return acc;
				}, {});

				// Envoie les données de vote au serveur
				$.ajax({
					url: './utils/saveVote.php',
					type: 'POST',
					data: {
						"res": res
					},
				}).done(function(e) {
					console.log(e);
					let helper = JSON.parse(e);
					AlertSuccess('Vote success', `You have successfully voted for ${helper.title}`, function() {
						location.reload();
					});
				})
			});

		})
	})

	// Inutile, pour tester
	$(document).ready(function() {
		$('.home').on('submit', '#absentForm', function(e) {
			e.preventDefault();
			let formData = $(this).serialize();

			let res = formData.split('&').reduce(function(acc, curr) {
				let parts = curr.split('=');
				if (!acc.assign) {
					acc.assign = [];
				}
				if (parts[0] === 'ballotId' || parts[0] === 'email') {
					acc[decodeURIComponent(parts[0])] = decodeURIComponent(parts[1]);
				} else {
					acc.assign.push(decodeURIComponent(parts[1]));
				}
				return acc;
			}, {})

			$.ajax({
				url: './utils/assignVote.php',
				type: 'POST',
				data: {
					"res": res
				},
			}).done(function(e) {
				$('#homeMain').hide();
				$('#voteMessage').html(e);
			})
		})
	})
</script>