<?php include '../../../utils/databaseHandler.php' ?>
<?php
    session_start();
    if (!isset($_SESSION['loggedin'])) {
        header('Location: ../../../index.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $filePath = '../../../database/ballots.json';
        $ballots = loadDataFromFile($filePath);

        // Récupère les données transmises via POST
        $res = $_POST['res'];
        $res_email = $res['email'];
        $res_ballotId = $res['ballotId'];
        $res_assign = $res['assign'];

        $ballot_title;

        // Parcourt tous les scrutins pour trouver celui concerné
        foreach($ballots as &$ballot) {
            if($ballot['id'] === $res_ballotId) {
                $ballot_title = $ballot['electionTitle'];
                // Réinitialise le compteur de votes de l'utilisateur se déclarant absent
                $ballot['voterList'][$res_email] = 0;

                // Incrémente le compteur de votes des utilisateurs assignés
                foreach($res_assign as $assign) {
                    $ballot['voterList'][$assign] ++;
                }
                break; // Sort de la boucle une fois le scrutin trouvé et modifié
                
            }
            
        }

        saveDataToFile($filePath, $ballots);
        echo 'You have successfully declared absent for ' .  $ballot_title ;
        echo '<br><a href="./home.php">Go back to Home</a>';
    }
?>