<?php include '../../../utils/databaseHandler.php' ?>
<?php
    session_start();
    // Vérifie si l'utilisateur est connecté. Si non, redirige vers la page d'accueil
    if (!isset($_SESSION['loggedin'])) {
        header('Location: ../../../index.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Charge les données des scrutins à partir du fichier
        $filePath = '../../../database/ballots.json';
        $ballots = loadDataFromFile($filePath);

        $res = $_POST['res'];

        // ID du scrutin concerné
        $res_ballotId = $res['ballotId'];
        // Emails des utilisateurs à qui le vote est transféré
        $res_transfer = $res['transfer'];

        $ballot_title;

        // Parcourt la liste des scrutins pour trouver le scrutin concerné
        foreach($ballots as &$ballot) {
            if($ballot['id'] === $res_ballotId) {
                $ballot_title = $ballot['electionTitle'];

                // Boucle sur la liste des transferts
                for($x = 0; $x < count($res_transfer); $x++) {
                    if($x == 0) {
                        // L'utilisateur déclaré absent a son droit de vote réinitialisé à 0
                        $ballot['voterList'][$res_transfer[$x]] = 0;
                    }else {
                        // Incrémente le droit de vote des utilisateurs à qui le vote est transféré
                        $ballot['voterList'][$res_transfer[$x]] ++;
                    }
                }
                break; // Sort de la boucle une fois le scrutin trouvé et modifié
                
            }
            
        }

        saveDataToFile($filePath, $ballots);
        echo 'You have successfully declared absent for ' .  $ballot_title ;
        echo '<br><a href="./home.php">Go back to Home</a>';
    }
?>