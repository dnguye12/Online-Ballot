<!DOCTYPE html>
<html lang="en">

<!-- Page d'inscription -->
<head>
	<?php include '../../utils/head.php'; ?>
	<link rel="stylesheet" href="utils/register.css">
</head>

<body>
	<header>
		<nav class="navbar shadow-sm">
			<div class="container">
				<a href="../../index.php">OnlineBallot</a>
			</div>
		</nav>
	</header>

	<!-- Section d'inscription -->
	<div class="register my-5">
		<div class="container">
			<h1>Register</h1>
			<p>If you already have an account, <a href="../login/login.php">Login</a></p>

			<!-- Formulaire d'inscription pour ajax -->
			<form id="registrationForm" autocomplete="off">
				<div class="mb-3">
				<label for="username" class="mb-2">
					<i class="fas fa-user"></i> Username
				</label><br>
				<input type="text" name="username" placeholder="Username" id="username" required>
				</div>

				<div class="mb-3">
				<label for="password" class="mb-2">
					<i class="fas fa-lock"></i> Password
				</label><br>
				<input type="password" name="password" placeholder="Password" id="password" required>
				</div>

				<div class="mb-3">
				<label for="email" class="mb-2">
					<i class="fas fa-envelope"></i> Email
				</label><br>
				<input type="email" name="email" placeholder="Email" id="email" required>
				</div>

				<input type="submit" value="Register" class="w-100">
			</form>
		</div>
	</div>

	<!-- Zone pour afficher les messages de retour de l'enregistrement de PHP/Ajax -->
	<div id="registrationMessage"  class="container my-5"></div>
</body>

</html>
<?php include '../../utils/foot.php' ?>
<script>
	// Script pour gérer la soumission du formulaire d'inscription
	$(document).ready(function() {
		$('#registrationForm').submit(function(e) {
			e.preventDefault();
			// Envoie les données du formulaire par AJAX
			$.ajax({
				type: 'POST',
				url: 'utils/registerAuth.php',
				data: $(this).serialize(),
			}).done(function(e) {
				console.log(e);
				// Cache le formulaire d'inscription en cas de succès
				if(e.includes("You have successfully registered!")) {
					$('.register').hide();
				}
				// Affiche le message de retou
				$('#registrationMessage').html(e);
			})
		})
	})
</script>