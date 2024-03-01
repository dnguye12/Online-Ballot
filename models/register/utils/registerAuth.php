<?php include '../../../utils/databaseHandler.php'?>
<?php
$filePath = '../../../database/accounts.json';

if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
    echo '<p>Please complete the registration form!</p>';
    echo '<a href="./register.php">Go Back</a>';
    exit;
}

if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
    echo '<p>Please complete the registration form!</p>';
    echo '<a href="./register.php">Go Back</a>';
    exit;
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo '<p>Email is not valid!</p>';
    echo '<a href="./register.php">Go Back</a>';
    exit;
}

if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
    echo '<p>Password must be between 5 and 20 characters long!</p>';
    echo '<a href="./register.php">Go Back</a>';
    exit;
}

if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    echo '<p>Username is not valid!</p>';
    echo '<a href="./register.php">Go Back</a>';
    exit;
}

$accounts = loadDataFromFile($filePath);

foreach ($accounts as $account) {
    if($account['username'] == $_POST['username']) {
        echo '<p>Username exists, please choose another!</p>';
        echo '<a href="./register.php">Go Back</a>';
        exit;
    }else if($account['email'] == $_POST['email']) {
        echo '<p>Email exists, please choose another!</p>';
        echo '<a href="./register.php">Go Back</a>';
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