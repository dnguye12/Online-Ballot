<?php 
    session_start();
    if (!isset($_SESSION['loggedin'])) {
        header('Location: ../../../index.php');
        exit;
    }

    $ballot = $_POST["ballot"];
    echo "<h2>" . $ballot['electionTitle'] . "</h2>";
    echo '<form id="voteForm">';
    echo "<input type='hidden' name='ballotId' value=" . $ballot['id'] . ">";
    echo "<input type='hidden' name='email' value=" . $_SESSION['email'] . ">";
    foreach ($ballot['questions'] as $question) {
        echo "<fieldset>";
        echo "<legend>" . $question['title'] . "</legend>";
        foreach($question['choices'] as $key => $value) {
            echo "<div>";
            echo "<input type='radio' id='$key' name=" . $question['title'] . " value='$key'>";
            echo "<label for='$key'>$key</label>";
            echo "</div>";
        }
        echo "</fieldset>";
    }
    echo '<input type="submit" value="Submit Ballot">';
    echo '</form>';
?>