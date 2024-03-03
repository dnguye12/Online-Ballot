<?php
function VoterBallot($ballot)
{
    require_once './utils/ballotService.php';
    $myVote = getRemainingVote($ballot);
    ob_start();

    $disableButton = ($myVote <= 0 or $ballot['status'] != "Running");
?>
    <div class="col-12 col-md-6 col-xl-4">
        <div class="ballot_voter border rounded shadow-sm p-3">
            <div class="ballot_header">
                <?php
                echo '<div class="d-flex align-items-center">';
                echo "<h4 class='mb-0'>" . $ballot["electionTitle"] . "</h4>";
                if (isset($ballot["groupName"]) && $ballot["groupName"] != "") {
                    echo "<p class='mb-0 ms-1'> — " . $ballot["groupName"] .  "</p>";
                }
                echo "</div>";
                echo "<p class='my-1 voteCount'>You have " . $myVote . " remaining votes.</p>";
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