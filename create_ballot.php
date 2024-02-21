<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'utils/head.php'; ?>

<body>
    <form id="createForm" action="utils/save_ballot.php" method="post">
        <label for="electionTitle">Title of the Election:</label>
        <input type="text" id="electionTitle" name="electionTitle" required><br>

        <label for="groupName">Name of Group / Organization (Optional):</label>
        <input type="text" id="groupName" name="groupName"><br>

        <div id="questionsContainer">
            <!-- Questions will be added here dynamically -->
        </div>

        <button type="button" onclick="addQuestion()">Add Question</button><br>

        <div id="votersContainer">

        </div>
        <button type="button" onclick="addVoter()">Add Voter Email</button>

        <button type="submit" onclick="submitForm()">Submit</button>
        <button type="button" onclick="window.location.href='home.php'">Cancel</button>
    </form>
</body>

</html>
<?php include 'utils/foot.php' ?>
<script src="utils/create_ballot.js"></script>