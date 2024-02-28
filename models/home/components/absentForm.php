<?php
require_once '../utils/ballotService.php';

session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../../index.php');
    exit;
}
$ballot = $_POST["ballot"];

$myVote = getRemainingVote($ballot);

echo "<h2>Declare absent</h2>";
echo "<p>You have to assign <span id='voteRemain'>" . $myVote . "</span> remaining votes.</p>";
echo '<form id="absentForm">';
echo "<input type='hidden' name='ballotId' value=" . $ballot['id'] . ">";
echo "<input type='hidden' name='email' value=" . $_SESSION['email'] . ">";

echo "<fieldset>";
echo "<legend>Choose the first person you want to assign your vote.</legend>";
foreach ($ballot['voterList'] as $k => $v) {
    if ($k != $_SESSION['email']) {
        echo "<input type='radio' name='absentPassOne' value='$k' class='absent-checkbox-one'>" . $k . "</input>";
    }
}
echo "</fieldset>";

if ($myVote == 2) {
    echo "<fieldset>";
    echo "<legend>Choose the second person you want to assign your vote.</legend>";
    foreach ($ballot['voterList'] as $k => $v) {
        if ($k != $_SESSION['email']) {
            echo "<input type='radio' name='absentPassTwo' value='$k' class='absent-checkbox-two'>" . $k . "</input>";
        }
    }
    echo "</fieldset>";
}
echo '<input type="submit" value="Submit Absent" id="submitAbsent" disabled>';
echo '<button type="button" onclick="window.location.href=\'../home/home.php\'">Cancel</button>';
echo '</form>';
?>

<script>
    $(document).ready(function() {
        $('input[name="absentPassOne"').change(function() {
            let selectedEmailOne = $(this).val();

            $('input[name="absentPassTwo"]').each(function() {
                if ($(this).val() === selectedEmailOne) {
                    $(this).prop('checked', false);
                }
            })

            checkSubmitCondition();
        });

        $('input[name="absentPassTwo"]').change(function() {
            let selectedEmailTwo = $(this).val();

            $('input[name="absentPassOne"]').each(function() {
                if ($(this).val() === selectedEmailTwo) {
                    $(this).prop('checked', false);
                }
            })
            checkSubmitCondition();
        });

        function setRemainingVotes(myVote, voteOneSelected, voteTwoSelected) {
            let helper1 = document.getElementById('voteRemain');
            let helper2 = myVote;
            if(voteOneSelected) {
                helper2--;
            }
            if(voteTwoSelected) {
                helper2--;
            }
            helper1.textContent = helper2;
        }

        function checkSubmitCondition() {
            let myVote = <?php echo $myVote; ?>;
            let voteOneSelected = $('input[name="absentPassOne"]:checked').length > 0;
            let voteTwoSelected = $('input[name="absentPassTwo"]:checked').length > 0;

            if (myVote === 1 && voteOneSelected) {
                $('#submitAbsent').prop('disabled', false);
            } else if (myVote === 2 && voteOneSelected && voteTwoSelected) {
                $('#submitAbsent').prop('disabled', false);
            } else {
                $('#submitAbsent').prop('disabled', true);
            }

            setRemainingVotes(myVote, voteOneSelected, voteTwoSelected);
        }
    })
</script>