<?php
function OrganizeBallot($ballot)
{
    ob_start();
?>

<!-- Définit la structure de base d'un scrutin dans le dashboard de l'organisateur -->
<div class="col-12 col-md-6 col-xl-4" id=<?php echo "organize_" . $ballot["id"]?>>
    <div class="ballot_org border rounded shadow-sm p-3">
        <div class="ballot_header">
            <?php
            // Affiche le titre du scrutin et, si présent, le nom du groupe associé
            echo '<div class="d-flex align-items-center">';
            echo "<h4 class='mb-0'>" . $ballot["electionTitle"] . "</h4>";
            if (isset($ballot["groupName"]) && $ballot["groupName"] != "") {
                echo "<p class='mb-0 ms-1'> — " . $ballot["groupName"] .  "</p>";
            }
            echo "</div>";

            // Indique le statut du scrutin avec un badge coloré
            if ($ballot['status'] == "Running") {
                echo "<span class='badge bg-primary'>Running</span>";
            } else if ($ballot['status'] == "Not started") {
                echo "<span class='badge bg-warning text-dark'>Not started</span>";
            } else {
                echo "<span class='badge bg-danger'>Closed</span>";
            }
            ?>
        </div>
        <div class="ballot_body d-flex flex-column justify-content-center align-items-start">
            <?php
            $ballotData = json_encode($ballot);
            ?>
            <div class="my-2">
                <!-- Boutons pour afficher les questions, les statistiques, transférer ou fermer le scrutin -->
            <button class="ballot-question btn shadow-sm rounded btn-outline-dark mt-1" data-ballot='<?php echo $ballotData; ?>'><i class="fa-solid fa-question me-1"></i>Questions</button>
            <button class="stat btn shadow-sm rounded btn-outline-dark mt-1" data-ballot='<?php echo $ballotData; ?>'><i class="fa-solid fa-chart-column me-1"></i>Ballot stats</button> 
            <button class="transfer-vote btn shadow-sm rounded btn-outline-primary mt-1" data-ballot='<?php echo $ballotData; ?>' <?php if($ballot['status'] == 'Closed') {echo 'disabled';}?>><i class="fa-solid fa-arrow-right-arrow-left me-1"></i>Transfer vote</button>
            </div>
            <div>
            <button class="close-ballot btn shadow-sm rounded btn-outline-danger mt-1 mt-sm-0" data-ballot='<?php echo $ballotData; ?>' <?php if($ballot['status'] == 'Closed') {echo 'disabled';}?>><i class="fa-solid fa-lock me-1"></i>Close ballot</button>
            <button class="delete-ballot btn shadow-sm rounded btn-outline-danger mt-1 mt-sm-0" data-ballot='<?php echo $ballotData; ?>' <?php if($ballot['status'] != 'Closed') {echo 'disabled';}?>><i class="fa-solid fa-trash me-1"></i>Delete ballot</button>
            </div>
        </div>
    </div>
    </div>
<?php
    $html = ob_get_clean();
    return $html;
}
?>