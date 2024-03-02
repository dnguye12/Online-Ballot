<script src="../../utils/alertHandler.js"></script>
<?php include '../../../utils/databaseHandler.php' ?>
<?php
$filePath = '../../../database/accounts.json';

if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
    echo '<script type="text/javascript">
        AlertError("Missing information", "Please complete the registration form!");
        </script>';
    exit;
}

if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
    echo '<script type="text/javascript">
        AlertError("Missing information", "Please complete the registration form!");
        </script>';
    exit;
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo '<script type="text/javascript">
        AlertError("Not a valid email", "Email is not valid!");
        </script>';
    exit;
}

if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
    echo '<script type="text/javascript">
        AlertError("Not valid password", "Password must be between 5 and 20 characters long!");
        </script>';
    exit;
}

if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    echo '<script type="text/javascript">
        AlertError("Not valid username", "Please do not use any special characters.");
        </script>';
    exit;
}

$accounts = loadDataFromFile($filePath);

foreach ($accounts as $account) {
    if ($account['username'] == $_POST['username']) {
        echo '<script type="text/javascript">
        AlertError("Username existed", "Username existed, please choose another!");
        </script>';
        exit;
    } else if ($account['email'] == $_POST['email']) {
        echo '<script type="text/javascript">
        AlertError("Email existed", "Email already existed, please choose another!");
        </script>';
        exit;
    }
}

$accounts[] = [
    'id' => count($accounts) + 1,
    'username' => $_POST['username'],
    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
    'email' => $_POST['email'],
];

saveDataToFile($filePath, $accounts);

echo '<p>You have successfully registered! You can now login!</p>';
echo '<a href="../login/login.php">Go to Login</a>';
?>