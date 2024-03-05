<?php
require_once '../utils/ballotService.php';

session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../../index.php');
    exit;
}
$ballot = $_POST["ballot"];

echo "<h2>Transfer vote</h2>";
echo '<form id="transferForm">';
echo "<input type='hidden' name='ballotId' value=" . $ballot['id'] . ">";

echo "<fieldset>";
echo "<legend>Pick the person to declare absent.</legend>";
foreach ($ballot['voterList'] as $k => $v) {
    if ($v > 0) {
        echo "<input type='radio' name='transferPassOne' value='$k' class='transfer-checkbox-one'>" . $k . "</input>";
    }
}
echo "</fieldset>";
echo '<input type="submit" value="Submit Transfer" id="submitTransfer" disabled>';
echo '<button type="button" onclick="window.location.href=\'../home/home.php\'">Cancel</button>';
echo '</form>';
?>

<script>
    $(document).ready(function() {
        $('input[name="transferPassOne"]').change(function() {
            let selectedEmailOne = $(this).val();
            let ballot = <?php echo json_encode($ballot); ?>;

            let voteCount = parseInt(ballot["voterList"][selectedEmailOne]);
            $('.dynamic-field').remove();
            if (voteCount >= 1) {
                addDynamicSelector(selectedEmailOne, voteCount, ballot);
            }
        });

        $('#transferForm').on('change', 'input[name="transferPassTwo"]', function() {
            let selectedEmailTwo = $('input[name="transferPassTwo"]:checked').val();
            let ballot = <?php echo json_encode($ballot); ?>;

            updateSelectionsTwo(selectedEmailTwo,ballot);
            updateSubmitButtons();
        });

        $('#transferForm').on('change', 'input[name="transferPassThree"]', function() {
            let selectedEmailThree = $('input[name="transferPassThree"]:checked').val();
            let ballot = <?php echo json_encode($ballot); ?>;

            updateSelectionsThree(selectedEmailThree, ballot);
            updateSubmitButtons();
        });
    });

    function addDynamicSelector(selectedEmailOne, voteCount, ballot) {
        let helper = "<fieldset class='dynamic-field dynamicOne'><legend>Pick the first person to give 1 vote:</legend>";
        $.each(ballot['voterList'], function(email, count) {
            if (email !== selectedEmailOne && count < 2 && ballot['votedList'][email] < 2) {
                helper += "<input type='radio' name='transferPassTwo' value='" + email + "' class='transfer-checkbox-two'>" + email + "</input>";
            }
        })
        helper += "</fieldset>";
        $('#transferForm').append(helper);

        if (voteCount == 2) {
            helper = "<fieldset class='dynamic-field dynamicTwo'><legend>Pick the second person to give 1 vote:</legend>";
            $.each(ballot['voterList'], function(email, count) {
                if (email !== selectedEmailOne && count < 2 && ballot['votedList'][email] < 2) {
                    helper += "<input type='radio' name='transferPassThree' value='" + email + "' class='transfer-checkbox-three'>" + email + "</input>";
                }
            })
            helper += "</fieldset>";
            $('#transferForm').append(helper);
        }
    }

    function updateSelectionsTwo(selectedEmailTwo, ballot) {
        if(ballot['voterList'][selectedEmailTwo] == 1 || ballot['votedList'][selectedEmailTwo] == 1) {
            $('input[name="transferPassThree"]').each(function() {
                let email = $(this).val();
                if(email === selectedEmailTwo) {
                    $(this).prop('checked', false);
                }
            })
        }
    }

    function updateSelectionsThree(selectedEmailThree, ballot) {
        if(ballot['voterList'][selectedEmailThree] == 1 || ballot['votedList'][selectedEmailThree] == 1) {
            $('input[name="transferPassTwo"]').each(function() {
                let email = $(this).val();
                if(email === selectedEmailThree) {
                    $(this).prop('checked', false);
                }
            })
        }
    }

    function updateSubmitButtons() {
        let hasTwoSelected = $('input[name="transferPassTwo"]:checked').length > 0;
        let hasThreeSelected = $('input[name="transferPassThree"]:checked').length > 0 || $('input[name="transferPassThree"]').length === 0;
        $('#submitTransfer').prop('disabled', !(hasTwoSelected && hasThreeSelected));
    }
</script>