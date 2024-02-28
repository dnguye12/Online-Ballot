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

        $res = $_POST['res'];

        $res_ballotId = $res['ballotId'];
        $res_transfer = $res['transfer'];

        $ballot_title;

        foreach($ballots as &$ballot) {
            if($ballot['id'] === $res_ballotId) {
                $ballot_title = $ballot['electionTitle'];

                for($x = 0; $x < count($res_transfer); $x++) {
                    if($x == 0) {
                        $ballot['voterList'][$res_transfer[$x]] = 0;
                    }else {
                        $ballot['voterList'][$res_transfer[$x]] ++;
                    }
                }
                break;
                
            }
            
        }

        saveDataToFile($filePath, $ballots);
        echo 'You have successfully declared absent for ' .  $ballot_title ;
        echo '<br><a href="./home.php">Go back to Home</a>';
    }
?>