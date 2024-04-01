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
        // Parcourt tous les scrutins pour trouver celui qui correspond à l'ID envoyé
        foreach($ballots as &$ballot) {
            if($ballot['id'] === $res['id']) {
                // Change le statut du scrutin trouvé en "Closed"
                $ballot['status'] = "Closed";
                break; // Sort de la boucle une fois le scrutin trouvé et modifié
                
            }
        }

        // Sauvegarde les modifications apportées aux scrutins dans le fichier JSON
        saveDataToFile($filePath, $ballots);

        echo json_encode([
            'id' =>  $res["id"],
            'title' => $res["electionTitle"]
        ]);
        exit;
    }
?>