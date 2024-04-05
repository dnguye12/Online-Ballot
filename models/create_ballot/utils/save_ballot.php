<?php include '../../../utils/databaseHandler.php' ?>
<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../../index.php');
    exit;
}

// Traite le formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Génère un identifiant unique pour le scrutin.
    $electionId = uniqid("election");

    // Récupère le titre de l'élection et le nom du groupe depuis les données POST.
    $electionTitle = $_POST['electionTitle'] ?? '';
    $groupName = $_POST['groupName'] ?? '';

    // Crée des objets DateTime pour les dates de début et de fin.
    $startDate = new DateTime($_POST['startDate']);
    $endDate = new DateTime($_POST['endDate']);
    $nowDate = new DateTime();

    // Détermine le statut du scrutin basé sur les dates.
    $status = "Running";
    if($nowDate  < $startDate) {
        $status = "Not started";
    }else if ($nowDate > $endDate) {
        $status = "Closed";
    }

    // Formate les dates pour l'enregistrement.
    $startDateFormatted = $startDate->format('Y-m-d H:i:s');
    $endDateFormatted = $endDate->format('Y-m-d H:i:s');

    // Charge les données existantes.
    $filePath = '../../../database/ballots.json';
    $existingData = loadDataFromFile($filePath);

    // Prépare les nouvelles données du scrutin.
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

    // Initialisation des tableaux pour les emails des votants validés
    $validatedVoterEmails = []; //tableu des electeurs
    $validatedVoterEmails2 = []; // tableau indiquant le nombre de votes effectués par chaque électeur
    
    // Itère sur les données POST pour trouver et organiser les questions et leurs choix.
    // Traite également les emails des votants pour s'assurer qu'ils sont valides.
    foreach ($_POST as $key => $value) {
        if (preg_match('/question(\d+)Title$/', $key, $matches)) {
            $questionIndex = $matches[1];
            $questionTitle = $value;

            $choices = [];

            foreach ($_POST as $choiceKey => $choiceValue) {
                if (preg_match("/question{$questionIndex}Choice(\d+)$/", $choiceKey, $choiceMatches)) {
                    if (!empty($choiceValue)) { 
                        $choices[$choiceValue] = 0;
                    }
                }
            }

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

    // Ajoute le nouveau scrutin aux données existantes et sauvegarde le tout dans le fichier JSON.
    $existingData[] = $newBallotData;

    saveDataToFile($filePath, $existingData);

    echo '<div class="container border rounded shadow-sm p-3">';
    echo $electionTitle .  ' have been successfully created!';
    echo '<br><a href="../home/home.php" class="btn btn-black mt-2 shadow-sm">Go back to Home</a>';
    echo '</div>';
    exit;
}
?>
