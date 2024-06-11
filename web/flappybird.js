let board;
let boardWidth = 360;
let boardHeight = 640;
let context;

let birdWidth = 34;
let birdHeight = 24;
let birdX = boardWidth/8;
let birdY = boardHeight/2;
let birdImg;

let bird = {
    x : birdX,
    y : birdY,
    width : birdWidth,
    height : birdHeight
}

let pipeArray = [];
let pipeWidth = 64;
let pipeHeight = 512;
let pipeX = boardWidth;
let pipeY = 0;

let topPipeImg;
let bottomPipeImg;

let velocityX = -1.5;
let velocityY = 0;
let gravity = 0.2;

let gameOver = true;
let score = 0;

window.onload = function() {
    board = document.getElementById("board");
    board.height = boardHeight;
    board.width = boardWidth;
    context = board.getContext("2d");

    birdImg = new Image();
    birdImg.src = "pictures/flappybird.png";
    birdImg.onload = function() {
        context.drawImage(birdImg, bird.x, bird.y, bird.width, bird.height);
    }

    topPipeImg = new Image();
    topPipeImg.src = "pictures/topbarrier.png";

    bottomPipeImg = new Image();
    bottomPipeImg.src = "pictures/buttonbarrier.png";

    requestAnimationFrame(update);
    setInterval(placePipes, 1500);
    document.addEventListener("keydown", moveBird);
}

function update() {
    requestAnimationFrame(update);
    if (gameOver) {
        saveScoreToDatabase(score);
        loadScore();
        return;
    }
    context.clearRect(0, 0, board.width, board.height);

    velocityY += gravity;
    bird.y = Math.max(bird.y + velocityY, 0);
    context.drawImage(birdImg, bird.x, bird.y, bird.width, bird.height);

    if (bird.y > board.height) {
        gameOver = true;
    }

    for (let i = 0; i < pipeArray.length; i++) {
        let pipe = pipeArray[i];
        pipe.x += velocityX;
        context.drawImage(pipe.img, pipe.x, pipe.y, pipe.width, pipe.height);

        if (!pipe.passed && bird.x > pipe.x + pipe.width) {
            score += 0.5;
            pipe.passed = true;
        }

        if (detectCollision(bird, pipe)) {
            gameOver = true;
        }
    }

    while (pipeArray.length > 0 && pipeArray[0].x < -pipeWidth) {
        pipeArray.shift();
    }

    context.fillStyle = "black";
    context.font="45px sans-serif";
    context.fillText(score, 5, 45);

    
}

function placePipes() {
    if (gameOver) {
        return;
    }

    let randomPipeY = pipeY - pipeHeight/4 - Math.random()*(pipeHeight/2);
    let openingSpace = board.height/4;

    let topPipe = {
        img : topPipeImg,
        x : pipeX,
        y : randomPipeY,
        width : pipeWidth,
        height : pipeHeight,
        passed : false
    }
    pipeArray.push(topPipe);

    let bottomPipe = {
        img : bottomPipeImg,
        x : pipeX,
        y : randomPipeY + pipeHeight + openingSpace,
        width : pipeWidth,
        height : pipeHeight,
        passed : false
    }
    pipeArray.push(bottomPipe);
}

function moveBird(e) {
    if (e.code == "Space") {
        velocityY = -6;
    }
}

function detectCollision(a, b) {
    return a.x < b.x + b.width &&
           a.x + a.width > b.x &&  
           a.y < b.y + b.height && 
           a.y + a.height > b.y;
}

function startGame() {
    if (gameOver) {
        bird.y = birdY;
        pipeArray = [];
        score = 0;
        gameOver = false;
    }
    
}

function restartGame() {
    startGame();
}

function returnToMenu() {
    document.getElementById('menu').style.display = 'block';
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
    xhr.open("POST", "flappybird-score.php", true);
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
    xhr.open("POST", "flappybird-load.php", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var scoreDiv = document.getElementById('playerscore');
            if (xhr.responseText !== '') {
                scoreDiv.innerHTML = 'Nejlepší skóre : ' + xhr.responseText;
            } else {
                scoreDiv.innerHTML = '';
            }
        }
    };
    xhr.send("nickname=" + encodeURIComponent('<?php echo $nickname; ?>'));
}


loadScore();