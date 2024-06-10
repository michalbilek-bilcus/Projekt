const levels = [
    { pairs: 3, attempts: 6 },
    
];

let currentLevel = 0;
let attemptsLeft = levels[currentLevel].attempts;
let firstCard = null;
let secondCard = null;
let matchesFound = 0;

const gameBoard = document.getElementById('game-board');
const attemptsDisplay = document.getElementById('attempts-left');
const timerDisplay = document.getElementById('timer');
const gameOverOverlay = document.getElementById('game-over-overlay');
const gameOverMessage = document.getElementById('game-over-message');
const restartButton = document.getElementById('restart-button');

const images = [
    'pictures/apple.png', 'pictures/bannan.png', 'pictures/carrot.png', 'pictures/cherries.png', 'pictures/citron.png',
    'pictures/grapes.png', 'pictures/meloun.png', 'pictures/pear.png', 'pictures/strawberry.png', 'pictures/tomato.png'
];

restartButton.addEventListener('click', resetGame);

function initializeGame() {
    const levelConfig = levels[currentLevel];
    const shuffledImages = shuffleArray(images.slice(0, levelConfig.pairs).concat(images.slice(0, levelConfig.pairs)));
    gameBoard.style.gridTemplateColumns = `repeat(${Math.ceil(Math.sqrt(levelConfig.pairs * 2))}, 100px)`;
    gameBoard.innerHTML = '';

    shuffledImages.forEach((imageSrc) => {
        const card = document.createElement('div');
        card.classList.add('card');
        card.dataset.image = imageSrc;
        card.addEventListener('click', () => flipCard(card));

        const img = document.createElement('img');
        img.src = imageSrc;
        card.appendChild(img);

        gameBoard.appendChild(card);
    });

    attemptsLeft = levelConfig.attempts;
    matchesFound = 0;
    firstCard = null;
    secondCard = null;
    attemptsDisplay.textContent = `Pokusy: ${attemptsLeft}`;
    gameOverOverlay.style.display = 'none';
}

function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

function flipCard(card) {
    if (attemptsLeft === 0) {
        return;
    }
    if (card.classList.contains('flipped') || (firstCard && secondCard) || matchesFound === levels[currentLevel].pairs) {
        return;
    }
    
    card.classList.add('flipped');
    if (!firstCard) {
        firstCard = card;
        
    } else {
        attemptsLeft--;
        secondCard = card;
        if (firstCard.dataset.image === secondCard.dataset.image) {
            matchesFound++;
            firstCard = null;
            secondCard = null;
            if (matchesFound === levels[currentLevel].pairs) {
                if (currentLevel !== levels.length - 1) {
                    currentLevel++;
                    setTimeout(initializeGame, 1000);
                } else {
                    saveTimeToDatabase(timeElapsed);
                    stopTimer();
                    loadScore();
                    resetGame();
                }
                
            }
        } 
        else if (attemptsLeft === 0) {
                showGameOver();
                stopTimer();
                setTimeout(() => {
                    showGameOver();
                }, 1000);          
        } else {
            setTimeout(() => {
                firstCard.classList.remove('flipped');
                secondCard.classList.remove('flipped');
                firstCard = null;
                secondCard = null;
            }, 1000);
        }
    }
    attemptsDisplay.textContent = `Pokusy: ${attemptsLeft}`;
}

function showGameOver() {
    gameOverMessage.textContent = "Konec hry. Došli pokusy";
    gameOverOverlay.style.display = 'flex';
}

function resetGame() {
    loadScore();
    currentLevel = 0;
    resetTimer();
    startTimer();
    initializeGame();
}

var timerInterval;
var timeElapsed = 0;

function startTimer() {
    timerInterval = setInterval(updateTime, 1000);
}

function stopTimer() {
    clearInterval(timerInterval);
}

function updateTime() {
    timeElapsed++;
    timerDisplay.textContent = `Čas: ${timeElapsed}`;
}

function resetTimer() {
    stopTimer();
    timeElapsed = 0;
    timerDisplay.textContent = `Čas: ${timeElapsed}`;
}

function saveTimeToDatabase(score) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "pexeso-time.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
        }
    };
    var data = "score=" + encodeURIComponent(score);
    xhr.send(data);
}

initializeGame();
startTimer();

function loadScore() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "pexeso-load.php", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var scoreDiv = document.getElementById('playerscore');
            if (xhr.responseText !== '') {
                scoreDiv.innerHTML = 'Nejlepší čas : ' + xhr.responseText;
            } else {
                scoreDiv.innerHTML = ''; // Vymaže obsah scoreDiv
            }
        }
    };
    xhr.send("nickname=" + encodeURIComponent('<?php echo $nickname; ?>'));
}

loadScore();
