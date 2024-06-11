document.addEventListener("DOMContentLoaded", () => {
    const startButton = document.getElementById('start-button');
    const gameBoard = document.getElementById('game-board');
    const livesDisplay = document.getElementById('lives');
    const scoreDisplay = document.getElementById('score');
    const overlay = document.getElementById('game-over-overlay');
    const gameOverMessage = document.getElementById('game-over-message');
    const restartButton = document.getElementById('restart-button');
    const scoreDiv = document.getElementById('playerscore');

    let lives;
    let score;
    let gameInterval;
    let spawnInterval;
    let gameActive = false;

    const objects = [
        { id: 1, points: 1, color: 'red', timeout: 1500 },
        { id: 2, points: 2, color: 'blue', timeout: 1000 },
        { id: 3, points: 3, color: 'green', timeout: 500 }
    ];

    function startGame() {
        loadScore();
        if (gameActive) return;
        gameActive = true;

        lives = 3;
        score = 0;
        spawnInterval = 1500;
        livesDisplay.textContent = lives;
        scoreDisplay.textContent = score;
        overlay.style.display = 'none';

        gameInterval = setInterval(spawnObject, spawnInterval);

        gameBoard.addEventListener('click', clickHandler);
    }

    function endGame() {
        gameActive = false;
        clearInterval(gameInterval);
        gameBoard.innerHTML = '';
        gameBoard.removeEventListener('click', clickHandler);
        saveScoreToDatabase(score);
        overlay.style.display = 'flex';
        gameOverMessage.textContent = `You lost, score: ${score}`;
    }

    function spawnObject() {
        const randomObject = objects[Math.floor(Math.random() * objects.length)];
        const objectElement = document.createElement('div');
        objectElement.classList.add('card');
        objectElement.style.backgroundColor = randomObject.color;
        objectElement.style.left = `${Math.random() * (gameBoard.clientWidth - 100)}px`;
        objectElement.style.top = `${Math.random() * (gameBoard.clientHeight - 100)}px`;
        objectElement.dataset.points = randomObject.points;
        
        objectElement.addEventListener('click', () => {
            score += randomObject.points;
            scoreDisplay.textContent = score;
            objectElement.remove();
        });

        gameBoard.appendChild(objectElement);

        setTimeout(() => {
            if (gameBoard.contains(objectElement)) {
                objectElement.remove();
                loseLife();
            }
        }, randomObject.timeout);
    }

    function loseLife() {
        lives -= 1;
        livesDisplay.textContent = lives;
        if (lives <= 0) {
            endGame();
        }
    }

    function clickHandler(event) {
        if (event.target === gameBoard) {
            loseLife();
        }
    }

    function updateTopPlayers(players) {
        var playersList = document.querySelector('.top-players ul');
        playersList.innerHTML = '';
        players.forEach(function(player) {
            var li = document.createElement('li');
            li.textContent = player.nickname + ": " + player.highscore;
            playersList.appendChild(li);
        });
    }

    function saveScoreToDatabase(score) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "klikacka-score.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                updateTopPlayers(JSON.parse(xhr.responseText));
            }
        };
        var data = "score=" + encodeURIComponent(score);
        xhr.send(data);
    }

    function loadScore() {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "klikacka-load.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText !== '') {
                    scoreDiv.innerHTML = 'Nejlepší skóre: ' + xhr.responseText;
                } else {
                    scoreDiv.innerHTML = '';
                }              
            }
        };
        xhr.send("nickname=" + encodeURIComponent('<?php echo $nickname; ?>'));
    }

    startButton.addEventListener('click', startGame);
    restartButton.addEventListener('click', startGame);
    loadScore();
});
