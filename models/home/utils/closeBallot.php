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

        $res = $_POST['ballot'];
        foreach($ballots as &$ballot) {
            if($ballot['id'] === $res['id']) {
                $ballot['status'] = "Closed";
                break;
                
            }
        }

        saveDataToFile($filePath, $ballots);
        echo 'You have successfully closed ' .  $res['electionTitle'] ;
        echo '<br><a href="./home.php">Go back to Home</a>';
    }
?>