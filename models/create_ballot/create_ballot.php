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
    <link rel="stylesheet" href="./utils/create_ballot.css">
</head>

<body class="create_ballot_form">
    <!-- Formulaire pour créer un nouveau scrutin -->
    <div class="container border rounded shadow-sm p-3 mt-5 mb-4">
        <h4 class="mb-0">Create a new Election</h4>
    </div>
    <form id="createForm " class="container border rounded shadow-sm p-3 mb-5">
        <label for="electionTitle" class="mb-2">Title of the Election:</label>
        <input type="text" id="electionTitle" name="electionTitle" placeholder="Title of the Election" required class="mb-3"><br>

        <label for="groupName" class="mb-2">Name of Group / Organization (Optional):</label>
        <input type="text" id="groupName" name="groupName" placeholder="Name of Group / Organization" class="mb-3"><br>

        <label for="startDate" class="mb-2">Start of the Election:</label>
        <input type="datetime-local" id="startDate" name="startDate" class="mb-3"><br>

        <label for="endDate" class="mb-2">End of the Election:</label>
        <input type="datetime-local" id="endDate" name="endDate" class="mb-3"><br>

        <div id="questionsContainer">
            <!-- Questions will be added here dynamically -->
        </div>

        <button type="button" onclick="addQuestion()" class="mt-1 mb-3 btn btn-black">Add Question</button><br>

        <div id="votersContainer">

        </div>
        <button type="button" onclick="addVoter()" class="mt-1 btn btn-black">Add Voter Email</button>

        <div class="d-flex align-items-center mt-3">
        <button type="submit" class="me-1 btn-orange btn">Submit</button>
        <button type="button" onclick="window.location.href='../home/home.php'" class="btn-black btn">Cancel</button>
</div>
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
        if(questionCount == 1) {
        questionDiv.className = "border-top border-bottom mb-2";
        }else {
            questionDiv.className = "border-bottom mb-2";
        }
        questionDiv.innerHTML = `
        <label for="question${questionCount}Title" class="my-2">Question ${questionCount}:</label>
        <input type="text" id="question${questionCount}Title" name="question${questionCount}Title" placeholder="Question ${questionCount}" Title" required class="mb-3"><br>
        <div id="choicesContainer${questionCount}" class="mb-2">
            <!-- Choices for question ${questionCount} will be added here -->
        </div>
        <div class="d-flex align-items-center mb-3">
        <button type="button" onclick="addChoice(${questionCount})" class="me-1 btn btn-black">Add Choice</button>
        <button type="button" onclick="deleteElement('question${questionCount}')" class="btn btn-outline-danger">Remove Question</button>
        </div>
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
        <input type="text" id="question${questionNumber}Choice${choiceCount}" name="question${questionNumber}Choice${choiceCount}" placeholder="Question ${questionNumber} - Choice ${choiceCount}" class="mb-2" required>
        <button type="button" onclick="deleteElement('${choiceDiv.id}')" class="mb-2 btn btn-outline-danger">Delete Choice</button>
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
        if(voterCount == 1) {
            voterDiv.className = "border-top border-bottom mb-2";
        }else {
            voterDiv.className = "border-bottom mb-2";
        }
        voterDiv.innerHTML = `
    <label for="voter${voterCount}" class="my-2">Email ${voterCount}:</label>
    <input type="text" id="voter${voterCount}" name="voter${voterCount}" placeholder="Voter ${voterCount}" required class="mb-3"><br>
    <button type="button" onclick="deleteElement('voter${voterCount}')" class="mb-3 btn btn-outline-danger">Remove Voter Email</button>
    `;
        votersContainer.appendChild(voterDiv);
    }

    // Supprime dynamiquement un élément (question, choix de réponse, email de votant) du formulaire
    function deleteElement(id) {
        const element = document.getElementById(id);
        if(id.includes('question')) {
            questionCount--;
            if(questionCount < 0) {
                questionCount = 0;
            }
        }
        if (element) {
            element.parentNode.removeChild(element);
        }
    }
</script>