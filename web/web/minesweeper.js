var width = 9,
    height = 9,
    numBombs = 5,
    cellSize = 64;

var canvas = document.getElementById('board');
canvas.width = width * cellSize;
canvas.height = height * cellSize;
var context = canvas.getContext('2d');

var gameStarted = false;

var image = new Image();
image.src = 'pictures/flag.png'; 
var image2 = new Image();
image2.src = 'pictures/bombabum.png';

image.onload = function() {
    draw(); 
};
image2.onload = function() {
    draw();
};

function Cell(x, y) {
    this.x = x;
    this.y = y;
    this.isBomb = false;
    this.isFlagged = false;
    this.isOpen = false;
}


Cell.prototype.draw = function (context, field) {
    context.save();
    context.translate(this.x * cellSize, this.y * cellSize);
    context.fillStyle = this.isOpen ? '#eee' : '#bbb';
    context.fillRect(1, 1, cellSize - 2, cellSize - 2);
    var s = '';
    if (this.isFlagged) {
        context.drawImage(image, 0, 0, cellSize, cellSize);
    } else if (this.isOpen) {
        var bombsAround = this.cellsAround(field).filter(c => c.isBomb).length;
        if (this.isBomb) {
            context.drawImage(image2, 0, 0, cellSize, cellSize);
        } else if (bombsAround > 0) {
            context.fillStyle = '#000';
            s = bombsAround;
        }
    }
    context.textAlign = 'center';
    context.font = '20px Verdana';
    context.fillText(s, cellSize / 2, cellSize / 2 + 10);
    context.restore();
};


Cell.prototype.cellsAround = function (field) {
    var cells = [];
    for (var yy = -1; yy <= 1; yy++) {
        var cy = this.y + yy;
        if (cy < 0 || cy >= height) continue;
        for (var xx = -1; xx <= 1; xx++) {
            if (xx == 0 && yy == 0) continue;
            var cx = this.x + xx;
            if (cx < 0 || cx >= width) continue;
            cells.push(field[cy][cx]);
        }
    }
    return cells;
};


Cell.prototype.flag = function () {
    if (!this.isOpen) this.isFlagged = !this.isFlagged;
    return true;
};


Cell.prototype.click = function (field) {
    if (this.isOpen) return true;
    if (this.isBomb) return false;
    this.isOpen = true;
    var cells = this.cellsAround(field);
    if (cells.filter(c => c.isBomb).length == 0) cells.forEach(c => c.click(field));
    return true;
};


var field = init();

function init() {
    var cells = [];
    for (var y = 0; y < height; y++) {
        var row = [];
        for (var x = 0; x < width; x++) row.push(new Cell(x, y));
        cells.push(row);
    }
    for (var i = 0; i < numBombs; i++) {
        while (true) {
            var x = Math.floor(width * Math.random()),
                y = Math.floor(height * Math.random());
            if (!cells[y][x].isBomb) {
                cells[y][x].isBomb = true;
                break;
            }
        }
    }
    return cells;
}

function draw() {
    eachCell(cell => cell.draw(context, field));
}

function openAll() {
    eachCell(cell => cell.isOpen = true);
}

function eachCell(fn) {
    for (var y = 0; y < height; y++) {
        for (var x = 0; x < width; x++) fn(field[y][x]);
    }
}


function gameWon() {
    var found = 0;
    eachCell(cell => { if (cell.isBomb && cell.isFlagged) found++; });
    var gameWon = numBombs === found;
    if (gameWon) {
        stopTimer();
    }
    return gameWon;
}

function finishGame(text) {
    saveTimeToDatabase(timeElapsed);
    openAll();
    draw();
    stopTimer();


    var overlay = document.getElementById('overlay');
    var gameResultElement = document.getElementById('game-result');


    gameResultElement.textContent = text;


    overlay.style.display = 'flex';

    setTimeout(function () {
        window.location.reload();
    }, 3000);
}



var numFlags = 0;

function processAction(x, y, fn) {
    var cell = field[Math.floor(y / cellSize)][Math.floor(x / cellSize)];
    var ok = fn(cell);
    draw();
    if (!ok) finishGame('Prohrál jsi!');
    if (gameWon()) finishGame('Vyhrál jsi!');
    updateFlagsInfo();
}

draw();
function StartGame() {
    gameStarted = true;
    draw();
    startTimer(); 
    updateFlagsInfo();
    canvas.addEventListener('click', function(e) {
        var rect = canvas.getBoundingClientRect();
        var mouseX = e.clientX - rect.left;
        var mouseY = e.clientY - rect.top - 15;
        processAction(mouseX, mouseY, (cell) => cell.click(field));
    });
    canvas.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        var rect = canvas.getBoundingClientRect();
        var mouseX = e.clientX - rect.left;
        var mouseY = e.clientY - rect.top - 15;
        if (gameStarted && numFlags < numBombs) {
            processAction(mouseX, mouseY, (cell) => {
                if (!cell.isOpen) {
                    if (!cell.isFlagged) {
                        if (numFlags < numBombs) {
                            cell.flag();
                            numFlags++;
                        }
                    } else {
                        cell.flag();
                        numFlags--;
                    }
                } else if (cell.isFlagged) {
                    cell.isFlagged = false;
                    numFlags--;
                }
                return true;
            });
        }
    });
}

function ResetGame() {
    loadScore();
    field = init(); 
    numFlags = 0;
    updateFlagsInfo();
    gameStarted = false;
    draw(); 
    resetTimer();
    StartGame();
    R();
}
function R() {
    field = init(); 
    numFlags = 0;
    updateFlagsInfo();
    gameStarted = false;
    draw(); 
    resetTimer();
    StartGame();
}




function updateFlagsInfo() {
    var flagsCountElement = document.getElementById('flags-count');
    flagsCountElement.textContent = numBombs - numFlags;
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
    document.getElementById('time-count').textContent = timeElapsed;
}

function resetTimer() {
    stopTimer();
    timeElapsed = 0;
    document.getElementById('time-count').textContent = timeElapsed;
}

function saveTimeToDatabase(score) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "minesweeper-time.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {

        }
    };
    var data = "score=" + encodeURIComponent(score);

    xhr.send(data);
}
StartGame();
function loadScore() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "minesweeper-load.php", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var scoreDiv = document.getElementById('playerscore');
            if (xhr.responseText === 'null') {
                scoreDiv.innerHTML = 'Ještě nemáš nejlepší čas';
            } else if (xhr.responseText !== '') {
                scoreDiv.innerHTML = 'Nejlepší čas: ' + xhr.responseText;
            } else {
                scoreDiv.innerHTML = '';
            }
        }
    };
    xhr.send("nickname=" + encodeURIComponent('<?php echo $nickname; ?>'));
}

loadScore();