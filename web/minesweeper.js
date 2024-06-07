// Definice herních parametrů
var width = 9,
    height = 9,
    numBombs = 5,
    cellSize = 64;

// Získání reference na plátno (canvas) a nastavení jeho rozměrů
var canvas = document.getElementById('board');
canvas.width = width * cellSize;
canvas.height = height * cellSize;
var context = canvas.getContext('2d');

var gameStarted = false; // Proměnná pro kontrolu, zda hra byla spuštěna

var image = new Image();
image.src = 'pictures/flag.png'; // Změňte na správnou cestu k obrázku
var image2 = new Image();
image2.src = 'pictures/bombabum.png';

// Když se obrázek načte, vykreslí se vlajka
image.onload = function() {
    draw(); // Vyvoláme kreslení pole
};
image2.onload = function() {
    draw(); // Vyvoláme kreslení pole
};
// Konstruktor buňky
function Cell(x, y) {
    this.x = x;
    this.y = y;
    this.isBomb = false;
    this.isFlagged = false;
    this.isOpen = false;
}

// Metoda pro vykreslení buňky
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

// Metoda pro získání okolních buněk
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

// Metoda pro označení vlajkou
Cell.prototype.flag = function () {
    if (!this.isOpen) this.isFlagged = !this.isFlagged;
    return true;
};

// Metoda pro otevření buňky
Cell.prototype.click = function (field) {
    if (this.isOpen) return true;
    if (this.isBomb) return false;
    this.isOpen = true;
    var cells = this.cellsAround(field);
    if (cells.filter(c => c.isBomb).length == 0) cells.forEach(c => c.click(field));
    return true;
};

// Inicializace herního pole
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

// Vykreslení herního pole
function draw() {
    eachCell(cell => cell.draw(context, field));
}

// Otevření všech buněk
function openAll() {
    eachCell(cell => cell.isOpen = true);
}

// Iterace přes všechny buňky a volání funkce fn
function eachCell(fn) {
    for (var y = 0; y < height; y++) {
        for (var x = 0; x < width; x++) fn(field[y][x]);
    }
}

// Funkce pro zjištění, zda hráč vyhrál
function gameWon() {
    var found = 0;
    eachCell(cell => { if (cell.isBomb && cell.isFlagged) found++; });
    var gameWon = numBombs === found;
    if (gameWon) {
        stopTimer(); // Zastavení časovače
    }
    return gameWon;
}

function finishGame(text) {
    saveTimeToDatabase(timeElapsed);
    openAll();
    draw();
    stopTimer(); // Zastavení časovače

    // Získání elementů pro overlay a výsledek hry
    var overlay = document.getElementById('overlay');
    var gameResultElement = document.getElementById('game-result');

    // Nastavení textu výsledku hry
    gameResultElement.textContent = text;

    // Zobrazení overlay
    overlay.style.display = 'flex';

    // Reload stránky po krátké pauze (pro lepší vizuální efekt)
    setTimeout(function () {
        window.location.reload();
    }, 3000); // Počkej 3 sekundy a poté přejdi na novou hru
}


// Počet umístěných vlajek
var numFlags = 0;

// Zpracování akce na buňce
function processAction(x, y, fn) {
    var cell = field[Math.floor(y / cellSize)][Math.floor(x / cellSize)];
    var ok = fn(cell);
    draw();
    if (!ok) finishGame('Game over, you lost!');
    if (gameWon()) finishGame('You won!');
    updateFlagsInfo(); // Aktualizace informací o vlajkách
}

// Vykreslení herního pole a začátek hry
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




// Aktualizace informací o vlajkách
function updateFlagsInfo() {
    var flagsCountElement = document.getElementById('flags-count');
    flagsCountElement.textContent = numBombs - numFlags; // Vypočítá zbývající počet vlajek
}

// Proměnné pro časovač
var timerInterval;
var timeElapsed = 0;

// Spuštění časovače
function startTimer() {
    timerInterval = setInterval(updateTime, 1000);
}

// Zastavení časovače
function stopTimer() {
    clearInterval(timerInterval);
}

// Aktualizace času
function updateTime() {
    timeElapsed++;
    document.getElementById('time-count').textContent = timeElapsed;
}

// Resetování časovače
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
            if (xhr.responseText !== '') {
                scoreDiv.innerHTML = 'Best time : ' + xhr.responseText;
            } else {
                scoreDiv.innerHTML = ''; // Vymaže obsah scoreDiv
            }
        }
    };
    xhr.send("nickname=" + encodeURIComponent('<?php echo $nickname; ?>'));
}

loadScore();