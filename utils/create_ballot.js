$('#createForm').on('keyup keypress', function(e) {
    let keyCode = e.keyCode || e.which;
    if(keyCode === 13) {
        e.preventDefault();
        return false;
    }
});

let questionCount = 0;

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

function deleteElement(id) {
    const element = document.getElementById(id);
    if(element) {
        element.parentNode.removeChild(element);
    }
}