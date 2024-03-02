<!DOCTYPE html>
<html lang="en">

<head>
	<?php include '../../utils/head.php'; ?>
	<link rel="stylesheet" href="./utils/login.css">
</head>

<body>
	<header>
		<nav class="navbar shadow-sm">
			<div class="container">
				<a href="../../index.php">OnlineBallot</a>
			</div>
		</nav>
	</header>
	<div class="login my-5">
		<div class="container">
			<h1>Login</h1>
			<form id="loginForm">
				<div class="mb-3">
					<label for="email" class="mb-2">
						<i class="fas fa-user"></i> Email
					</label><br>
					<input type="email" name="email" placeholder="Your Email" id="email" required>
				</div>

				<div class="mb-3">
					<label for="password" class="mb-2">
						<i class="fas fa-lock"></i> Password
					</label><br>
					<input type="password" name="password" placeholder="Password" id="password" required>
				</div>

				<input type="submit" value="Login" class="w-100">
			</form>
		</div>
	</div>
	<div id="loginMessage" class="container my-5"></div>
</body>

</html>
<?php include '../../utils/foot.php' ?>
<script>
	$(document).ready(function() {
		$('#loginForm').submit(function(e) {
			e.preventDefault();
			$.ajax({
				type: 'POST',
				url: 'utils/loginAuth.php',
				data: $(this).serialize(),
			}).done(function(e) {
				if (e.includes("success")) {
					window.location.href = '../home/home.php';
				} else {
					$('#loginMessage').html(e);
				}
			})
		})
	})
</script>