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

        $helper = array_filter($ballots, function($ballot) use ($res) {
            if($ballot['id'] === $res['id']) {
                return false;
            }
            return true;
        });

        saveDataToFile($filePath, $helper);
        echo 'You have successfully deleted ' .  $res['electionTitle'] ;
        echo '<br><a href="./home.php">Go back to Home</a>';
    }
?>