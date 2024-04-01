<?php
require_once '../utils/ballotService.php';

session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../../index.php');
    exit;
}
$ballot = $_POST["ballot"];
echo "<div class='container my-5 shadow rounded border p-sm-4 p-3 transfer_vote'>";
echo "<h2>Transfer vote</h2>";
echo '<form id="transferForm">';
echo "<input type='hidden' name='ballotId' value=" . $ballot['id'] . ">";
echo "<legend class='mb-1'>Pick the person to declare absent.</legend>";

// Pour chaque électeur potentiel, crée une option de transfert si elle a des votes à transférer.
foreach ($ballot['voterList'] as $k => $v) {
    if ($v > 0) {
        echo "<div class='mb-1'>";
        echo "<input type='radio' name='transferPassOne' value='$k' class='transfer-checkbox-one me-1'>" . $k . "</input>";
        echo "</div>";
    }
}
// Boutons pour soumettre le transfert ou annuler l'action.
echo "<div class='mt-3'>";
echo '<input type="submit" value="Submit Transfer" id="submitTransfer" disabled class="btn shadow-sm btnSubmit me-1">';
echo '<button type="button" class="btnCancel btn shadow-sm" onclick="window.location.href=\'../home/home.php\'">Cancel</button>';
echo "</div>";
echo '</form>';
echo "</div>";
?>

<script>
    $(document).ready(function() {
        // Code pour ajouter dynamiquement des sélecteurs en fonction du nombre de votes à transférer.
        $('input[name="transferPassOne"]').change(function() {
            let selectedEmailOne = $(this).val();
            let ballot = <?php echo json_encode($ballot); ?>;

            let voteCount = parseInt(ballot["voterList"][selectedEmailOne]);
            $('.dynamic-field').remove();
            if (voteCount >= 1) {
                addDynamicSelector(selectedEmailOne, voteCount, ballot);
            }
        });

        // Gère les changements sur les sélecteurs dynamiques pour les transferts de votes.
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

    // Ajoute des champs de sélection dynamiques pour choisir les électeurs à qui transférer les votes.
    function addDynamicSelector(selectedEmailOne, voteCount, ballot) {
        let helper = "<fieldset class='dynamic-field dynamicOne mb-1'><legend>Pick the first person to give 1 vote:</legend>";
        $.each(ballot['voterList'], function(email, count) {
            if (email !== selectedEmailOne && count < 2 && ballot['votedList'][email] < 2) {
                helper += "<div class='mb-1'>";
                helper += "<input type='radio' name='transferPassTwo' value='" + email + "' class='transfer-checkbox-two me-1'>" + email + "</input>";
                helper += "</div>";
            }
        })
        helper += "</fieldset>";
        $(helper).insertBefore('#submitTransfer');

        //Si cet utilisateur peut transférer 2 votes, ajoutez un autre champ
        if (voteCount == 2) {
            helper = "<fieldset class='dynamic-field dynamicTwo mb-1'><legend>Pick the second person to give 1 vote:</legend>";
            $.each(ballot['voterList'], function(email, count) {
                if (email !== selectedEmailOne && count < 2 && ballot['votedList'][email] < 2) {
                    helper += "<div class='mb-1'>";
                    helper += "<input type='radio' name='transferPassThree' value='" + email + "' class='transfer-checkbox-three me-1'>" + email + "</input>";
                    helper += "</div>";
                }
            })
            helper += "</fieldset>";
            $(helper).insertBefore('#submitTransfer');
        }
    }

    // Met à jour les sélections en fonction des choix de l'utilisateur.
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

    // Active ou désactive le bouton de soumission en fonction des choix de l'utilisateur.
    function updateSubmitButtons() {
        let hasTwoSelected = $('input[name="transferPassTwo"]:checked').length > 0;
        let hasThreeSelected = $('input[name="transferPassThree"]:checked').length > 0 || $('input[name="transferPassThree"]').length === 0;
        $('#submitTransfer').prop('disabled', !(hasTwoSelected && hasThreeSelected));
    }
</script>