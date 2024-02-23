<!DOCTYPE html>
<html lang="en">
<?php include '../../utils/head.php'; ?>

<body>
	<div class="login">
		<h1>Login</h1>
		<form id="loginForm">
			<label for="email">
				<i class="fas fa-user"></i>
			</label>
			<input type="email" name="email" placeholder="Your Email" id="email" required>
			<label for="password">
				<i class="fas fa-lock"></i>
			</label>
			<input type="password" name="password" placeholder="Password" id="password" required>
			<input type="submit" value="Login">
		</form>
	</div>
	<div id="loginMessage"></div>
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
				if(e === 'success') {
					window.location.href = '../home/home.php';
				}else {
					$('.login').hide();
					$('#loginMessage').html(e);
				}
			})
		})
	})
</script>