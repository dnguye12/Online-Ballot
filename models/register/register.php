<!DOCTYPE html>
<html lang="en">

<head>
	<?php include '../../utils/head.php'; ?>
</head>

<body>
	<div class="register">
		<h1>Register</h1>
		<form id="registrationForm" autocomplete="off">
			<label for="username">
				<i class="fas fa-user"></i>
			</label>
			<input type="text" name="username" placeholder="Username" id="username" required>
			<label for="password">
				<i class="fas fa-lock"></i>
			</label>
			<input type="password" name="password" placeholder="Password" id="password" required>
			<label for="email">
				<i class="fas fa-envelope"></i>
			</label>
			<input type="email" name="email" placeholder="Email" id="email" required>
			<input type="submit" value="Register">
		</form>
	</div>
	<div id="registrationMessage"></div>
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
				$('.register').hide();
				$('#registrationMessage').html(e);
			})
		})
	})
</script>