<?php
function VoterBallot($ballot)
{
    // Inclut le fichier de service de scrutin pour utiliser la fonction getRemainingVote
    require_once './utils/ballotService.php';
    // Récupère le nombre de votes restants pour l'utilisateur sur ce scrutin
    $myVote = getRemainingVote($ballot);
    ob_start();

    // Détermine si le bouton de vote doit être désactivé quand 
    // l'électeur n'a plus de vote ou le scrutin ne se déroule plus
    $disableButton = ($myVote <= 0 or $ballot['status'] != "Running");
?>
    <!-- Génère le HTML pour un scrutin -->
    <div class="col-12 col-md-6 col-xl-4"  id=<?php echo "voter_" . $ballot["id"]?>>
        <div class="ballot_voter border rounded shadow-sm p-3">
            <div class="ballot_header">
                <?php
                // Affiche le titre du scrutin et, s'il existe, le nom du groupe associé
                echo '<div class="d-flex align-items-center">';
                echo "<h4 class='mb-0'>" . $ballot["electionTitle"] . "</h4>";
                if (isset($ballot["groupName"]) && $ballot["groupName"] != "") {
                    echo "<p class='mb-0 ms-1'> — " . $ballot["groupName"] .  "</p>";
                }
                echo "</div>";

                // Affiche le nombre de votes restants pour l'utilisateur
                echo "<p class='my-1 voteCount'>You have " . $myVote . " remaining votes.</p>";
                
                // Affiche le statut du scrutin
                if ($ballot['status'] == "Running") {
                    echo "<span class='badge bg-primary'>Running</span>";
                } else if ($ballot['status'] == "Not started") {
                    echo "<span class='badge bg-warning text-dark'>Not started</span>";
                } else {
                    echo "<span class='badge bg-danger'>Closed</span>";
                }

                ?>
            </div>
            <div class="ballot_body d-flex align-items-start mt-2">
                <?php
                $ballotData = json_encode($ballot);
                ?>
                <!-- Boutons pour commencer à voter et pour voir les statistiques du scrutin -->
                <button class="start-vote btn shadow-sm rounded btn-outline-primary me-1" data-ballot='<?php echo $ballotData; ?>' <?php if ($disableButton) {
                                                                                        echo 'disabled';
                                                                                    } ?>><i class="fa-solid fa-check-to-slot me-1"></i>Start Voting</button>
                <button class="stat btn shadow-sm rounded btn-outline-dark" data-ballot='<?php echo $ballotData; ?>'><i class="fa-solid fa-chart-column me-1"></i>Ballot stats</button>
            </div>
        </div>
    </div>
<?php
    $html = ob_get_clean();
    return $html;
}
?>