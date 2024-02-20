<!DOCTYPE html>
<html lang="en">
<?php include 'utils/head.php'; ?>
<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}
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
			<p>Welcome back, <?=htmlspecialchars($_SESSION['name'], ENT_QUOTES)?>!</p>
		</div>
		<div class="action">
			<a href="create_ballot.php">Create Ballot</a>
		</div>
	</body>
</html>
<?php include 'utils/foot.php' ?>