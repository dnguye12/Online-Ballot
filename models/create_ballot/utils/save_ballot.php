<?php include '../../../utils/databaseHandler.php' ?>
<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $electionId = uniqid("election");
    $electionTitle = $_POST['electionTitle'] ?? '';
    $groupName = $_POST['groupName'] ?? '';

    $startDate = new DateTime($_POST['startDate']);
    $endDate = new DateTime($_POST['endDate']);
    $nowDate = new DateTime();
    $status = "Running";
    if($nowDate  < $startDate) {
        $status = "Not started";
    }else if ($nowDate > $endDate) {
        $status = "Closed";
    }

    $startDateFormatted = $startDate->format('Y-m-d H:i:s');
    $endDateFormatted = $endDate->format('Y-m-d H:i:s');

    $filePath = '../../../database/ballots.json';
    $existingData = loadDataFromFile($filePath);

    $newBallotData = [
        "id" => $electionId,
        "electionTitle" => $electionTitle,
        "createBy" => $_SESSION['id'],
        "groupName" => $groupName,
        "startDate" => $startDateFormatted,
        "endDate" => $endDateFormatted,
        "status" => $status,
        "questions" => [],
        "voterList" => [],
        "votedList" => []
    ];
    $validatedVoterEmails = [];
    $validatedVoterEmails2 = [];
    // Iterate over $_POST to find questions and choices
    foreach ($_POST as $key => $value) {
        if (preg_match('/question(\d+)Title$/', $key, $matches)) {
            $questionIndex = $matches[1];
            $questionTitle = $value;

            // Initialize choices array for this question
            $choices = [];

            // Correct the pattern to match your JavaScript naming convention
            foreach ($_POST as $choiceKey => $choiceValue) {
                if (preg_match("/question{$questionIndex}Choice(\d+)$/", $choiceKey, $choiceMatches)) {
                    if (!empty($choiceValue)) { // Ensure the choice is not empty
                        // Use the matched choice number to ensure the order
                        $choices[$choiceValue] = 0;
                    }
                }
            }

            // Re-index choices array to remove any potential gaps

            $newBallotData['questions'][] = [
                "title" => $questionTitle,
                "choices" => $choices
            ];
        } else if (preg_match('/voter(\d+)/', $key, $matches)) {
            $voterEmail = trim($value);
            if (filter_var($voterEmail, FILTER_VALIDATE_EMAIL)) {
                $validatedVoterEmails[$voterEmail] = 1;
                $validatedVoterEmails2[$voterEmail] = 0;
            }
        }
    }
    $newBallotData['voterList'] = $validatedVoterEmails;
    $newBallotData['votedList'] = $validatedVoterEmails2;

    // Append new ballot data to existing data
    $existingData[] = $newBallotData; // Append the new ballot to the list

    // Encode to JSON and save
    saveDataToFile($filePath, $existingData);

    echo $electionTitle .  ' have been successfully created!';
    echo '<br><a href="../home/home.php">Go back to Home</a>';
    exit;
}
?>
