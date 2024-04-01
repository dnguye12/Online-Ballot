<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../../utils/head.php'; ?>
</head>

<body>
    <!-- Formulaire pour créer un nouveau scrutin -->
    <form id="createForm">
        <label for="electionTitle">Title of the Election:</label>
        <input type="text" id="electionTitle" name="electionTitle" required><br>

        <label for="groupName">Name of Group / Organization (Optional):</label>
        <input type="text" id="groupName" name="groupName"><br>

        <label for="startDate">Start of the Election:</label>
        <input type="datetime-local" id="startDate" name="startDate"><br>

        <label for="endDate">End of the Election:</label>
        <input type="datetime-local" id="endDate" name="endDate"><br>

        <div id="questionsContainer">
            <!-- Questions will be added here dynamically -->
        </div>

        <button type="button" onclick="addQuestion()">Add Question</button><br>

        <div id="votersContainer">

        </div>
        <button type="button" onclick="addVoter()">Add Voter Email</button>

        <button type="submit">Submit</button>
        <button type="button" onclick="window.location.href='../home/home.php'">Cancel</button>
    </form>
    <div id="createFormMessage"></div>
</body>

</html>
<?php include '../../utils/foot.php' ?>
<script>
    // Empêche la soumission du formulaire avec la touche Entrée pour éviter des envois accidentels
    $('#createForm').on('keyup keypress', function(e) {
        let keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });



    $(document).ready(function() {
        $('#createForm').submit(function(e) {
            e.preventDefault();
            var startDate = new Date($('#startDate').val());
            var endDate = new Date($('#endDate').val());
            if (endDate < startDate) {
                alert('The end date must be after the start date.');
            } else {
                //le formulaire est envoyé via AJAX
                $.ajax({
                    type: 'POST',
                    url: 'utils/save_ballot.php',
                    data: $(this).serialize(),
                }).done(function(e) {
                    $('#createForm').hide();
                    $('#createFormMessage').html(e);
                })
            }
        })
    })

    let questionCount = 0;

    // Ajoute dynamiquement un champ pour une nouvelle question avec des options de réponse
    function addQuestion() {
        questionCount++;
        const questionsContainer = document.getElementById('questionsContainer');
        const questionDiv = document.createElement('div');
        questionDiv.id = 'question' + questionCount;
        questionDiv.innerHTML = `
        <label for="question${questionCount}Title">Question:</label>
        <input type="text" id="question${questionCount}Title" name="question${questionCount}Title" required><br>
        <div id="choicesContainer${questionCount}">
            <!-- Choices for question ${questionCount} will be added here -->
        </div>
        <button type="button" onclick="addChoice(${questionCount})">Add Choice</button><br>
        <button type="button" onclick="deleteElement('question${questionCount}')">Remove Question</button>
    `;
        questionsContainer.appendChild(questionDiv);
        addChoice(questionCount); // Automatically add one choice field
    }

    // Ajoute dynamiquement un champ pour une nouvelle option de réponse pour une question spécifique
    function addChoice(questionNumber) {
        const choicesContainer = document.getElementById('choicesContainer' + questionNumber);
        const choiceCount = choicesContainer.childElementCount + 1;
        const choiceDiv = document.createElement('div');
        choiceDiv.id = `question${questionNumber}Choice${choiceCount}Div`;
        choiceDiv.innerHTML = `
        <input type="text" id="question${questionNumber}Choice${choiceCount}" name="question${questionNumber}Choice${choiceCount}" required>
        <button type="button" onclick="deleteElement('${choiceDiv.id}')">Delete Choice</button>
    `;
        choicesContainer.appendChild(choiceDiv);
    }

    let voterCount = 0;

    // Ajoute dynamiquement un champ pour entrer l'email d'un votant pour lui ajouter a ce scrutin
    function addVoter() {
        voterCount++;;
        const votersContainer = document.getElementById('votersContainer');
        const voterDiv = document.createElement('div');
        voterDiv.id = 'voter' + voterCount;
        voterDiv.innerHTML = `
    <label for="voter${voterCount}">Email ${voterCount}:</label>
    <input type="text" id="voter${voterCount}" name="voter${voterCount}" required><br>
    <button type="button" onclick="deleteElement('voter${voterCount}')">Remove Voter Email</button>
    `;
        votersContainer.appendChild(voterDiv);
    }

    // Supprime dynamiquement un élément (question, choix de réponse, email de votant) du formulaire
    function deleteElement(id) {
        const element = document.getElementById(id);
        if (element) {
            element.parentNode.removeChild(element);
        }
    }
</script>