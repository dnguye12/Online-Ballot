<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../../index.php');
    exit;
}

$ballot = $_POST["ballot"];

if(!empty($ballot['questions'])) {
// Initialisation des variables pour calculer les statistiques
$ballot_id = $ballot['id'];
$totalVote = 0;
$castedVote = 0;
$questionCount = count($ballot['questions']);
$optionCount = 0;

// Calcul du nombre total de votes possibles et des votes déjà effectués
foreach ($ballot['voterList'] as $k => $v) {
    $totalVote += $v;
}

foreach ($ballot['votedList'] as $k => $v) {
    $castedVote += $v;
}

// Calcul du nombre total d'options de réponse
foreach ($ballot['questions'] as $question) {
    $optionCount += count($question['choices']); 
}
$totalVote += $castedVote;
$rate = round((float)$castedVote / (float)$totalVote * 100, 2); // Pourcentage de participation

$statIdx = 1; // Indice pour le tableau des votes par utilisateur
$questionIdx = 1; // Indice pour numéroté les questions
}
?>

<div class="vote_header container border rounded shadow-sm p-3 my-4 mt-5">
    <?php
    echo '<div class="d-flex align-items-center">';
    echo "<h4 class='mb-0'>" . $ballot["electionTitle"] . "</h4>";
    if (isset($ballot["groupName"]) && $ballot["groupName"] != "") {
        echo "<p class='mb-0 ms-1'> — " . $ballot["groupName"] .  "</p>";
    }
    echo "</div>";
    ?>
</div>

<!-- Bloc pour les statistiques générales du scrutin -->
<div class="stat-holder <?php if(empty($ballot['questions'])) {echo "d-none";} ?>" id=<?php echo "stat-" . $ballot_id ?>>

    <div class="overview container my-4 px-sm-4 py-sm-4 px-3 py-3 rounded shadow-sm border ">
        <div class="row  g-3 ">
            <div class="col-12 col-md-6 castedVote">
                <div class="bg-success p-3 rounded shadow-sm d-flex justify-content-between align-items-center">
                    <i class="fa-solid fa-circle-check"></i>
                    <div class="d-flex flex-column align-items-end justify-content-center">
                        <h3 class="mb-0"><?php echo $rate ?>%</h3>
                        <p class="mb-0">Participation (<?php echo $castedVote ?> Votes Casted)</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 totalVote">
                <div class="bg-warning p-3 rounded shadow-sm d-flex justify-content-between align-items-center">
                    <i class="fa-solid fa-users"></i>
                    <div class="d-flex flex-column align-items-end justify-content-center">
                        <h3 class="mb-0"><?php echo $totalVote ?></h3>
                        <p class="mb-0">Votes Maximum</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 questionCount">
                <div class="bg-danger p-3 rounded shadow-sm d-flex justify-content-between align-items-center">
                    <i class="fa-solid fa-circle-question"></i>
                    <div class="d-flex flex-column align-items-end justify-content-center">
                        <h3 class="mb-0"><?php echo $questionCount ?></h3>
                        <p class="mb-0">Ballots Questions</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 optionCount">
                <div class="bg-info p-3 rounded shadow-sm d-flex justify-content-between align-items-center">
                    <i class="fa-solid fa-square-poll-vertical"></i>
                    <div class="d-flex flex-column align-items-end justify-content-center">
                        <h3 class="mb-0"><?php echo $optionCount ?></h3>
                        <p class="mb-0">Options</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="questions container my-4 px-sm-4 px-3 rounded shadow-sm border">
        <?php
        foreach ($ballot['questions'] as $question) {
            echo "<div class='my-sm-4 my-3 p-2 " . $question['title'] . "'>";
            echo "<div class='question_header'>";
            echo "<h3><b>Question" . $questionIdx . " </b>: " . $question['title'] . "</h3>";
            $questionIdx++;
            echo "<p class='mb-0'>Vote Breakdown</p>";
            echo "</div>";
            $helper = 0;
            foreach ($question['choices'] as $k => $v) {
                $helper += $v;
            }
            echo "<div class='question_body'>";
            if ($helper != 0) {
                foreach ($question['choices'] as $k => $v) {
                    echo "<div class='mt-2'>";
                    echo "<div class='d-flex justify-content-between align-items-center'>";
                    echo "<p class='mb-1'>" . $k .  "</p>";
                    echo "<p class='mb-1'>$v Votes(" . round((float)$v / (float)$helper * 100, 2) . "%)</p>";
                    echo "</div>";
                    echo "<div class='progress'>";
                    echo "<div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' aria-valuenow='$v' aria-valuemin='0' aria-valuemax='$helper' style='width: " . $v / $helper * 100 . "%'></div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                foreach ($question['choices'] as $k => $v) {
                    echo "<div class='mt-2'>";
                    echo "<div class='d-flex justify-content-between align-items-center'>";
                    echo "<p class='mb-1'>" . $k .  "</p>";
                    echo "<p class='mb-1'>0 Votes(0%)</p>";
                    echo "</div>";
                    echo "<div class='progress'>";
                    echo "<div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='1' style='width: 0%'></div>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>
    <div class="counts container my-4 mb-5 <?php if ($ballot["createBy"] != $_SESSION["id"]) {
                                            echo 'd-none';
                                        } ?>">
        <table class="table table-striped table-hover table-bordered shadow-sm">
            <thread>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Email</th>
                    <th scope="col">Remaining votes</th>
                    <th scope="col">Voted count</th>
                </tr>
            </thread>
            <tbody>
                <?php
                foreach ($ballot['votedList'] as $h => $v) {
                    echo "<tr>";
                    echo "<th scope='row'>" . $statIdx . "</th>";
                    echo "<td>$h</td>";
                    echo "<td>" . $ballot['voterList'][$h] . "</td>";
                    echo "<td>$v</td>";
                    echo "</tr>";
                    $statIdx++;
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- Bouton pour retourner à la page d'accueil -->
    <div class="container mt-3 mb-5 p-0">
        <button type="button" onclick="window.location.href='../home/home.php'" class="btnCancel btn shadow-sm"><i class="fa-regular fa-circle-xmark me-1"></i>Back to home</button>
    </div>
</div>

<!-- Aucunes statistiques pour un scrutin sans questions -->
<div class="stat-empty  <?php if(!empty($ballot['questions'])) {echo "d-none";} ?>">
    <div class="container rounded border top-3 p-3 shadow-sm">
        <p class="mb-0">No stats to display</p>
    </div>
    <!-- Bouton pour retourner à la page d'accueil -->
    <div class="container mt-3 mb-5 p-0">
        <button type="button" onclick="window.location.href='../home/home.php'" class="btnCancel btn shadow-sm"><i class="fa-regular fa-circle-xmark me-1"></i>Back to home</button>
    </div>
</div>