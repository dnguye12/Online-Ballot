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
        $res_questions = $res['questions'];

        $ballot_title;

        foreach($ballots as &$ballot) {
            if($ballot['id'] === $res_ballotId) {
                $ballot_title = $ballot['electionTitle'];
                $ballot['voterList'][$res_email] = $ballot['voterList'][$res_email] - 1;
                $ballot['votedList'][$res_email] = $ballot['votedList'][$res_email] + 1;

                for($x = 0; $x < count($ballot['questions']); $x++) {
                    $ballot['questions'][$x]['choices'][$res_questions[$x]]++;
                }
                break;
            }
        }

        saveDataToFile($filePath, $ballots);
        echo json_encode([
            'id' =>  $res_ballotId,
            'title' => $ballot_title
        ]);
        exit;
    }
?>