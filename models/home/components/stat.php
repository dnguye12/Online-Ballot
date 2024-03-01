<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../../index.php');
    exit;
}

$ballot = $_POST["ballot"];

$ballot_id = $ballot['id'];
$totalVote = 0;
$castedVote = 0;
$questionCount = count($ballot['questions']);
$optionCount = 0;

foreach ($ballot['voterList'] as $k => $v) {
    $totalVote += $v;
}

foreach ($ballot['votedList'] as $k => $v) {
    $castedVote += $v;
}

foreach ($ballot['questions'] as $question) {
    $optionCount += count($question['choices']);
}
$totalVote += $castedVote;
$rate = round((float)$castedVote / (float)$totalVote * 100, 2);
?>


<div id=<?php echo "stat-" . $ballot_id ?>>
    <div class="overview">
        <div class="castedVote">
            <i class="fa-solid fa-circle-check"></i>
            <div>
                <h3><?php echo $rate ?>%</h3>
                <p>Participation (<?php echo $castedVote ?> Votes Casted)</p>
            </div>
        </div>
        <div class="totalVote">
            <i class="fa-solid fa-users"></i>
            <div>
                <h3><?php echo $totalVote ?></h3>
                <p>Votes Maximum</p>
            </div>
        </div>
        <div class="questionCount">
            <i class="fa-solid fa-circle-question"></i>
            <div>
                <h3><?php echo $questionCount ?></h3>
                <p>Ballots Questions</p>
            </div>
        </div>
        <div class="optionCount">
            <i class="fa-solid fa-square-poll-vertical"></i>
            <div>
                <h3><?php echo $optionCount ?></h3>
                <p>Options</p>
            </div>
        </div>
    </div>
    <div class="questions">
        <?php
        foreach ($ballot['questions'] as $question) {
            echo "<div class=" . $question['title'] . ">";
            echo "<h3>" . $question['title'] . "</h3>";
            echo "<p>Vote Breakdown</p>";
            $helper = 0;
            foreach ($question['choices'] as $k => $v) {
                $helper += $v;
            }
            if ($helper != 0) {
                foreach ($question['choices'] as $k => $v) {
                    echo "<div>";
                    echo "<div>";
                    echo "<p>" . $k .  "</p>";
                    echo "<p>$v Votes(" . round((float)$v / (float)$helper * 100, 2) . "%)</p>";
                    echo "</div>";
                    echo "<progress value='$v' max='$helper'></progress>";
                    echo "</div>";
                }
            } else {
                echo "<div>";
                    echo "<div>";
                    echo "<p>" . $k .  "</p>";
                    echo "<p>0 Votes(0%)</p>";
                    echo "</div>";
                    echo "<progress value=0 max=0></progress>";
                    echo "</div>";
            }
            echo "</div>";
        }
        ?>
    </div>
</div>