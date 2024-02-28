<?php
    session_start();
    if (!isset($_SESSION['loggedin'])) {
        header('Location: ../../../index.php');
        exit;
    }

    $ballot = $_POST["ballot"];
    echo "<div>";
    echo "<h2>" . $ballot["electionTitle"] . "</h2>";
    if (isset($ballot["groupName"]) && $ballot["groupName"] != "") {
        echo "<p> — " . $ballot["groupName"] .  "</p>";
    }
    foreach($ballot['questions'] as $question) {
        echo "<h4>" . $question['title'] .  "</h4>";
        foreach($question['choices'] as $k => $v) {
            echo '<input type="radio" disabled>' . $k . '</input>';
        }
    }
    echo '<button type="button" onclick="window.location.href=\'../home/home.php\'">Back to home</button>';
    echo "</div>";

?>