<script src="../../utils/alertHandler.js"></script>
<?php include '../../../utils/databaseHandler.php' ?>
<?php
$filePath = '../../../database/accounts.json';

// Vérifie si les champs username, password et email sont présents dans les données d'inscription POST
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
    echo '<script type="text/javascript">
        AlertError("Missing information", "Please complete the registration form!");
        </script>';
    exit;
}

// Vérifie si les champs username, password et email d'inscriptions ne sont pas vides
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
    echo '<script type="text/javascript">
        AlertError("Missing information", "Please complete the registration form!");
        </script>';
    exit;
}

// Valide le format de l'email
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo '<script type="text/javascript">
        AlertError("Not a valid email", "Email is not valid!");
        </script>';
    exit;
}

// Vérifie la longueur du mot de passe
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
    echo '<script type="text/javascript">
        AlertError("Not valid password", "Password must be between 5 and 20 characters long!");
        </script>';
    exit;
}

// Vérifie le format du nom d'utilisateur (doit contenir uniquement des lettres et des chiffres)
if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    echo '<script type="text/javascript">
        AlertError("Not valid username", "Please do not use any special characters.");
        </script>';
    exit;
}

// Charge les comptes existants depuis le fichier
$accounts = loadDataFromFile($filePath);

// Parcourt les comptes existants pour vérifier les doublons
foreach ($accounts as $account) {
    // Vérifie si le nom d'utilisateur existe déjà
    if ($account['username'] == $_POST['username']) {
        echo '<script type="text/javascript">
        AlertError("Username existed", "Username existed, please choose another!");
        </script>';
        exit;
    } else if ($account['email'] == $_POST['email']) { // Affiche une alerte si l'email existe déjà
        echo '<script type="text/javascript">
        AlertError("Email existed", "Email already existed, please choose another!");
        </script>';
        exit;
    }
}

// Ajoute le nouveau compte au tableau des comptes
$accounts[] = [
    'id' => count($accounts) + 1,
    'username' => $_POST['username'],
    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
    'email' => $_POST['email'],
];

// Sauvegarde le tableau des comptes mis à jour dans le fichier
saveDataToFile($filePath, $accounts);

// Affiche un message de succès et un lien vers la page de connexion
echo '<p>You have successfully registered! You can now login!</p>';
echo '<a href="../login/login.php">Go to Login</a>';
?>