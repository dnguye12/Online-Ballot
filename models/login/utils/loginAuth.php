<script src="../../utils/alertHandler.js"></script>
<?php include '../../../utils/databaseHandler.php' ?>
<?php
session_start();

$jsonFilePath = '../../../database/accounts.json';

// Vérifie si les champs email et mot de passe ont été envoyés
if (!isset($_POST['email'], $_POST['password'])) {
    echo '<script type="text/javascript">
        AlertError("Missing information", "Please fill both the email and password fields!");
        </script>';
    exit;
}

// Charge les comptes depuis le base de donnes
$accounts = loadDataFromFile($jsonFilePath);
$foundAccount = null;

// Parcourt les comptes pour trouver une correspondance avec l'email
foreach ($accounts as $account) {
    if ($account['email'] == $_POST['email']) {
        $foundAccount = $account;
        break;
    }
}

// Vérifie si un compte correspondant a été trouvé
if ($foundAccount !== null) {
    // Vérifie si le mot de passe correspond
    if (password_verify($_POST['password'], $foundAccount['password'])) {
        session_regenerate_id(); // Régénère l'ID de session pour la sécurité
        
        // Enregistre les informations de l'utilisateur dans la session
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $foundAccount['username'];
        $_SESSION['id'] = $foundAccount['id'];
        $_SESSION['email'] = $_POST['email'];
        
        // Envoie une réponse de succès
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