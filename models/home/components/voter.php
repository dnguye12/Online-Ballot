<?php
function VoterBallot($ballot)
{
    require_once './utils/ballotService.php';
    $myVote = getRemainingVote($ballot);
    ob_start();
?>
    <div class="ballot_voter border">
        <div class="ballot_header">
            <?php
            if ($ballot['status'] == "Running") {
                echo "<span class='badge bg-primary'>Running</span>";
            }  else if ($ballot['status'] == "Not started") {
                echo "<span class='badge bg-warning text-dark'>Not started</span>";
            } else {
                echo "<span class='badge bg-danger'>Closed</span>";
            }
            echo "<h4>" . $ballot["electionTitle"] . "</h4>";
            if (isset($ballot["groupName"]) && $ballot["groupName"] != "") {
                echo "<p> — " . $ballot["groupName"] .  "</p>";
            }
            echo "<p>You have " . $myVote . " remaining votes.</p>";
            ?>
        </div>
        <div class="ballot_body">
            <?php
                $ballotData = json_encode($ballot);
            ?>
            <button class="start-vote"  data-ballot='<?php echo $ballotData; ?>' <?php if($myVote <= 0) {echo 'disabled';}?>>Start Voting</button>
            <button>Election stats</button>
            <button <?php if($myVote <= 0) {echo 'disabled';}?>>Declare absent</button>
        </div>
    </div>
<?php
    $html = ob_get_clean();
    return $html;
}
?>