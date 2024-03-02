<!DOCTYPE html>
<html lang="en">

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
	<div class="register my-5">
		<div class="container">
			<h1>Register</h1>
			<p>If you already have an account, <a href="../login/login.php">Login</a></p>
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
	<div id="registrationMessage"  class="container my-5"></div>
</body>

</html>
<?php include '../../utils/foot.php' ?>
<script>
	$(document).ready(function() {
		$('#registrationForm').submit(function(e) {
			e.preventDefault();
			$.ajax({
				type: 'POST',
				url: 'utils/registerAuth.php',
				data: $(this).serialize(),
			}).done(function(e) {
				console.log(e);
				if(e.includes("You have successfully registered!")) {
					$('.register').hide();
				}
				$('#registrationMessage').html(e);
			})
		})
	})
</script>