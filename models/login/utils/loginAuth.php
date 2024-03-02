<script src="../../utils/alertHandler.js"></script>
<?php include '../../../utils/databaseHandler.php' ?>
<?php
session_start();

$jsonFilePath = '../../../database/accounts.json';

if (!isset($_POST['email'], $_POST['password'])) {
    echo '<script type="text/javascript">
        AlertError("Missing information", "Please fill both the email and password fields!");
        </script>';
    exit;
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
        echo '<script type="text/javascript">
        AlertError("Incorrect email and/or password", "Please re check your login information!");
        </script>';
    exit;
    }
}else {
    echo '<script type="text/javascript">
        AlertError("Incorrect email and/or password", "Please re check your login information!");
        </script>';
    exit;
}
?>