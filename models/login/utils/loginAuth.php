<?php include '../../../utils/databaseHandler.php' ?>
<?php
session_start();

$jsonFilePath = '../../../database/accounts.json';

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
    if (password_verify($_POST['password'], $foundAccount['password'])) {
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $foundAccount['username'];
        $_SESSION['id'] = $foundAccount['id'];
        $_SESSION['email'] = $_POST['email'];
        echo "success";
        exit;
    }else {
        echo 'Incorrect email and/or password!';
        echo '<br><a href="login.php">Go Back</a>';
    }
}else {
    echo 'Incorrect email and/or password!';
    echo '<br><a href="login.php">Go Back</a>';
}
?>