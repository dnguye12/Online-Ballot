<?php include '../../../utils/databaseHandler.php' ?>
<?php
    session_start();
    // Vérification si l'utilisateur est connecté, sinon redirection vers la page d'accueil
    if (!isset($_SESSION['loggedin'])) {
        header('Location: ../../../index.php');
        exit;
    }

    // Traitement des données envoyées par méthode POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Chargement des scrutins à partir du fichier
        $filePath = '../../../database/ballots.json';
        $ballots = loadDataFromFile($filePath);

        // Récupération des données transmises via POST
        $res = $_POST['res'];
        $res_email = $res['email'];
        $res_ballotId = $res['ballotId'];
        $res_questions = $res['questions'];

        $ballot_title;

        // Parcours des scrutins pour trouver celui correspondant et mettre à jour les votes
        foreach($ballots as &$ballot) {
            if($ballot['id'] === $res_ballotId) {
                // Enregistrement du titre du scrutin pour la réponse
                $ballot_title = $ballot['electionTitle'];

                // Mise à jour du nombre de votes restants et effectués pour cet électeur
                $ballot['voterList'][$res_email] = $ballot['voterList'][$res_email] - 1;
                $ballot['votedList'][$res_email] = $ballot['votedList'][$res_email] + 1;

                // Incrémentation du nombre de votes du scrutin vote pour chaque choix sélectionné
                for($x = 0; $x < count($ballot['questions']); $x++) {
                    $ballot['questions'][$x]['choices'][$res_questions[$x]]++;
                }
                break;
            }
        }

        // Sauvegarde des scrutins mis à jour dans le fichier
        saveDataToFile($filePath, $ballots);

        // Réponse sous forme de JSON contenant l'ID et le titre du scrutin mis à jour
        echo json_encode([
            'id' =>  $res_ballotId,
            'title' => $ballot_title
        ]);
        exit;
    }
?>