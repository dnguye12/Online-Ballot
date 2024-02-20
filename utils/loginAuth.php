<?php include './head.php'; ?>
<?php include 'databaseHandler.php' ?>
<?php
session_start();

$jsonFilePath = '../database/accounts.json';

if (!isset($_POST['username'], $_POST['password'])) {
    exit('Please fill both the username and password fields!');
}

$accounts = loadDataFromFile($jsonFilePath);
$foundAccount = null;
foreach ($accounts as $account) {
    if ($account['username'] == $_POST['username']) {
        $foundAccount = $account;
        break;
    }
}

if ($foundAccount !== null) {
    echo "test";
    if (password_verify($_POST['password'], $foundAccount['password'])) {
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $_POST['username'];
        $_SESSION['id'] = $id;
        header('Location: ../home.php');
        exit;
    }else {
        echo 'Incorrect username and/or password!';
    }
}else {
    echo 'Incorrect username and/or password!';
}
?>
<?php include './foot.php' ?>