<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../../index.php');
    exit;
}

$ballot = $_POST["ballot"];
?>
<div class="vote_header container border rounded shadow-sm p-3 mt-5">
    <?php
    echo '<div class="d-flex align-items-center">';
    echo "<h4 class='mb-0'>" . $ballot["electionTitle"] . "</h4>";
    if (isset($ballot["groupName"]) && $ballot["groupName"] != "") {
        echo "<p class='mb-0 ms-1'> — " . $ballot["groupName"] .  "</p>";
    }
    echo "</div>";
    ?>
</div>

<!-- Container pour le formulaire de vote -->
<div class="voteFormContainer">
    <form id="voteForm">
        <?php
        // Variable d'aide pour numéroter les questions
        $helper = 1;

        // Champ caché pour stocker l'ID du scrutin et l'email de l'utilisateur
        echo "<input type='hidden' name='ballotId' value=" . $ballot['id'] . ">";
        echo "<input type='hidden' name='email' value=" . $_SESSION['email'] . ">";

        // Génère dynamiquement les questions et les choix sous forme de boutons radio
        foreach ($ballot['questions'] as $question) {
            echo "<fieldset class='container shadow-sm border rounded p-3 mt-3'>";
            echo "<legend><b>Question $helper </b>: " . $question['title'] . "</legend>";
            $helper++;
            echo "<div class='d-flex flex-column'>";
            foreach ($question['choices'] as $key => $value) {
                echo "<input type='radio' id=" . $question['title'] . $key . " name=" . $question['title'] . " value='$key'>";
                echo "<label for=" . $question['title'] . $key . " class='d-flex p-3 mb-2 border'>$key</label>";
            }
            echo "</div>";
            echo "</fieldset>";
        }

        ?>
        <!-- Boutons pour soumettre ou annuler le vote -->
        <div class="container mt-3 mb-5 p-0">
        <button type="submit" value="Submit" class="btnSubmit btn shadow-sm"><i class="fa-solid fa-check-to-slot me-2"></i>Submit Ballot</button>
        <button type="button" onclick="window.location.href='../home/home.php'" class="btnCancel btn shadow-sm"><i class="fa-regular fa-circle-xmark me-1"></i>Cancel</button>
        </div>
    </form>
</div>

<?php
?>