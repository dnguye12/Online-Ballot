<?php include 'databaseHandler.php' ?>
<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $electionTitle = $_POST['electionTitle'] ?? '';
    $groupName = $_POST['groupName'] ?? '';

    $filePath = '../database/ballots.json';
    $existingData = loadDataFromFile($filePath);

    $newBallotData = [
        "electionTitle" => $electionTitle,
        "createBy" => $_SESSION['id'],
        "groupName" => $groupName,
        "questions" => [],
        "voterList" => []
    ];
    $validatedVoterEmails = [];
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
                        $choices[(int)$choiceMatches[1]] = $choiceValue;
                    }
                }
            }

            // Re-index choices array to remove any potential gaps
            $choices = array_values($choices);

            $newBallotData['questions'][] = [
                "title" => $questionTitle,
                "choices" => $choices
            ];
        }else if(preg_match('/voter(\d+)/', $key, $matches)) {
            $voterEmail = trim($value);
            if(filter_var($voterEmail, FILTER_VALIDATE_EMAIL)) {
                $validatedVoterEmails[] = $voterEmail;
            }
        }
    }
    $newBallotData['voterList'] = $validatedVoterEmails;
    print_r($newBallotData);

    // Append new ballot data to existing data
    $existingData[] = $newBallotData; // Append the new ballot to the list

    // Encode to JSON and save
    saveDataToFile($filePath, $existingData);

    // Redirect to home.php
    header('Location: ../home.php');
    exit;
}
?>
