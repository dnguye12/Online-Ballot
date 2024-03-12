<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../../index.php');
    exit;
}

$ballot = $_POST["ballot"];
?>
<div class="questions_header container border rounded shadow-sm p-3 mt-5">
    <?php
    echo '<div class="d-flex align-items-center">';
    echo "<h4 class='mb-0'>" . $ballot["electionTitle"] . "</h4>";
    if (isset($ballot["groupName"]) && $ballot["groupName"] != "") {
        echo "<p class='mb-0 ms-1'> — " . $ballot["groupName"] .  "</p>";
    }
    echo "</div>";
    ?>
</div>
<div class="questions_container">
    <?php
    $helper = 1;
    foreach ($ballot['questions'] as $question) {
        echo "<fieldset class='container shadow-sm border rounded p-3 mt-3'>";
        echo "<legend><b>Question $helper </b>: " . $question['title'] . "</legend>";
        $helper++;
        echo "<div class='d-flex flex-column'>";
        foreach ($question['choices'] as $key => $value) {
            echo "<input type='radio' disabled id=" . $question['title'] . $key . " name=" . $question['title'] . " value='$key'>";
            echo "<label for=" . $question['title'] . $key . " class='d-flex p-3 mb-2 border'>$key</label>";
        }
        echo "</div>";
        echo "</fieldset>";
    }
    ?>
    <div class="container mt-3 mb-5 p-0">
        <button type="button" onclick="window.location.href='../home/home.php'" class="btnCancel btn shadow-sm"><i class="fa-regular fa-circle-xmark me-1"></i>Back to home</button>
    </div>

</div>
<?php
?>