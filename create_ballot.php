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
    <form id="createForm">
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

        <button type="submit">Submit</button>
        <button type="button" onclick="window.location.href='home.php'">Cancel</button>
    </form>
    <div id="createFormMessage"></div>
</body>

</html>
<?php include 'utils/foot.php' ?>
<script src="utils/create_ballot.js"></script>
<script>
    $(document).ready(function() {
        $('#createForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'utils/save_ballot.php',
                data: $(this).serialize(),
            }).done(function(e) {
                $('#createForm').hide();
                $('#createFormMessage').html(e);

            })
        })
    })
</script>