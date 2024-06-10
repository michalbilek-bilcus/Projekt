const canvas = document.querySelector('canvas');
const c = canvas.getContext('2d');

canvas.width = 500;
canvas.height = 640;
canvas.style.backgroundColor = 'lightblue';

var players = [];
class player {
    constructor(x, y, width, height, imageSrc) {
        this.x = x;
        this.y = y+50;
        this.width = width;
        this.height = height;
        this.speed = 5;
        this.image = new Image();
        this.image.src = imageSrc;
    }
    draw() {
        c.drawImage(this.image, this.x, this.y, this.width, this.height);
    }
    update() {
        this.draw();
        if (left) this.x -= this.speed;
        if (right) this.x += this.speed;
        if (this.x < 0) {
            this.x = 0;
        }
        if (this.x + this.width > canvas.width) {
            this.x = canvas.width - this.width;
        }
    }
}

const playerImageSrc = 'pictures/playerminer.png';
const playerHeight = 100;
const playerWidth = 100;
players.push(new player(canvas.width/2 - playerWidth/2, canvas.height -30 -10 - playerHeight, playerWidth, playerHeight, playerImageSrc));


var items = [];
class item {
    constructor(x, y, width, height, score, yVel, imageSrc, type){
        this.x = x;
        this.y = y;
        this.width = width;
        this.height = height;
        this.score = score;
        this.yVel = yVel;
        this.image = new Image();
        this.image.src = imageSrc;
        this.type = type;
    }
    draw() {
        c.drawImage(this.image, this.x, this.y, this.width, this.height);
    }

    update() {
        this.draw();

        this.y += this.yVel;
        setInterval(() => {
            items.forEach((item) => {
                item.yVel += 0.05;
            });
        }, 10000);
        
    }
}

setInterval(() => {
    const itemTypes = [
        { src: 'pictures/ametist.png', score: 1, yVel: 3, width: 50, height: 50, type: 'normal' },
        { src: 'pictures/Zlato.png', score: 2, yVel: 5, width: 50, height: 50, type: 'normal' },
        { src: 'pictures/diamand.png', score: 3, yVel: 7, width: 50, height: 50, type: 'normal' },
        { src: 'pictures/bombabum.png', score: 0, yVel: 4, width: 50, height: 50, type: 'bomb' }
    ];

    const randomIndex = Math.floor(Math.random() * itemTypes.length);
    const { src, score, yVel, width, height, type } = itemTypes[randomIndex];
    items.push(new item(Math.random() * ((canvas.width - width) - width) + width, -10, width, height, score, yVel, src, type));

}, 1000);

var score = 0;
var scoreElement = document.getElementById('score');

function Fall() {
    requestAnimationFrame(Fall);
    c.clearRect(0, 0, canvas.width, canvas.height)

    items.forEach((item, index) => {
        item.update();

        players.forEach((player) => {
            if (
                item.x + item.width > player.x &&
                item.x < player.x + player.width &&
                item.y + item.height > player.y &&
                item.y < player.y + player.height
            ) {
                
                score += item.score;
                scoreElement.textContent = score; 
                if (item.type === 'bomb') { 
                    saveScoreToDatabase(score);
                    loadScore();
                    score = 0;                  
                    location.reload();
                }
                items.splice(index, 1);
            }
        });

        if (item.y + item.height > players[0].y+80) {
            setTimeout(function () {
                items.splice(index, 1);
            }, 0);   
        }
    });

    players.forEach((player) => {
        player.update();
    });
}



var left;
var right;
function restartGame() {
    items = [];
    Fall();
}


document.addEventListener('keydown', (event) => {
    if (event.key == 'a') {
        left = true;
    }
    else if (event.key == 'd') {
        right = true;
    }
});

document.addEventListener('keyup', (event) => {
    if (event.key === 'd') {
        right = false;
    }
    else if (event.key === 'a') {
        left = false;
    }
});

function saveScoreToDatabase(score) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "chytanipredmetu-score.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {

        }
    };
    var data = "score=" + encodeURIComponent(score);

    xhr.send(data);
}
function loadScore() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "chytanipredmetu-load.php", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var scoreDiv = document.getElementById('playerscore');
            if (xhr.responseText !== '') {
                scoreDiv.innerHTML = 'Největší skóre : ' + xhr.responseText;
            } else {
                scoreDiv.innerHTML = '';
            }
        }
    };
    xhr.send("nickname=" + encodeURIComponent('<?php echo $nickname; ?>'));
}

loadScore();