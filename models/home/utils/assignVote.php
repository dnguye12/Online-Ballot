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
        $res_email = $res['email'];
        $res_ballotId = $res['ballotId'];
        $res_assign = $res['assign'];

        $ballot_title;

        foreach($ballots as &$ballot) {
            if($ballot['id'] === $res_ballotId) {
                $ballot_title = $ballot['electionTitle'];
                $ballot['voterList'][$res_email] = 0;

                foreach($res_assign as $assign) {
                    $ballot['voterList'][$assign] ++;
                }
                break;
                
            }
            
        }

        saveDataToFile($filePath, $ballots);
        echo 'You have successfully declared absent for ' .  $ballot_title ;
        echo '<br><a href="./home.php">Go back to Home</a>';
    }
?>