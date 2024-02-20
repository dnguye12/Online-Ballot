<?php include 'databaseHandler.php'?>
<?php
$filePath = '../database/accounts.json';

if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
    echo 'Please complete the registration form!';
    echo '<br><a href="../register.php">Go Back</a>';
    exit;
}

if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
    echo 'Please complete the registration form!';
    echo '<br><a href="../register.php">Go Back</a>';
    exit;
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo 'Email is not valid!';
    echo '<br><a href="../register.php">Go Back</a>';
    exit;
}

if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
    echo 'Password must be between 5 and 20 characters long!';
    echo '<br><a href="../register.php">Go Back</a>';
    exit;
}

if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    echo 'Username is not valid!';
    echo '<br><a href="../register.php">Go Back</a>';
    exit;
}

$accounts = loadDataFromFile($filePath);

foreach ($accounts as $account) {
    if($account['username'] == $_POST['username']) {
        echo 'Username exists, please choose another!';
        echo '<br><a href="../register.php">Go Back</a>';
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

echo 'You have successfully registered! You can now login!';
echo '<br><a href="../login.php">Go to Login</a>';
?>