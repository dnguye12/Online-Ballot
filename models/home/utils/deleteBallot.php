<?php include '../../../utils/databaseHandler.php' ?>
<?php
session_start();
// Vérifie si l'utilisateur est connecté. Si non, redirige vers la page d'accueil
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filePath = '../../../database/ballots.json';
    $ballots = loadDataFromFile($filePath);

    $res = $_POST['ballot'];

    // Filtre les scrutins pour exclure celui à supprimer
    $helper = array_filter($ballots, function ($ballot) use ($res) {
        // Retourne false pour le scrutin à supprimer, l'excluant du résultat final
        if ($ballot['id'] === $res['id']) {
            return false;
        }
        return true;
    });

    saveDataToFile($filePath, $helper);
    echo json_encode([
        'id' =>  $res["id"],
        'title' => $res["electionTitle"]
    ]);
    exit;
}
?>