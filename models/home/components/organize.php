<?php
function OrganizeBallot($ballot)
{
    ob_start();
?>
    <div class="ballot_org border">
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
            ?>
        </div>
        <div class="ballot_body">
            <button>Ballot questions</button>
            <button>Ballot stats</button>
            <button>Close ballot</button>
        </div>
    </div>
<?php
    $html = ob_get_clean();
    return $html;
}
?>