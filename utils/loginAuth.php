<?php include './head.php'; ?>
<?php include 'databaseHandler.php' ?>
<?php
session_start();

$jsonFilePath = '../database/accounts.json';

if (!isset($_POST['email'], $_POST['password'])) {
    exit('Please fill both the email and password fields!');
}

$accounts = loadDataFromFile($jsonFilePath);
$foundAccount = null;
foreach ($accounts as $account) {
    if ($account['email'] == $_POST['email']) {
        $foundAccount = $account;
        break;
    }
}

if ($foundAccount !== null) {
    echo "test";
    if (password_verify($_POST['password'], $foundAccount['password'])) {
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $foundAccount['username'];
        $_SESSION['id'] = $foundAccount['id'];
        $_SESSION['email'] = $_POST['email'];
        header('Location: ../home.php');
        exit;
    }else {
        echo 'Incorrect email and/or password!';
    }
}else {
    echo 'Incorrect email and/or password!';
}
?>
<?php include './foot.php' ?>