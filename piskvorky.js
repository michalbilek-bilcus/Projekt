const board = document.getElementById('board');
let currentPlayer = 'X';
let gameBoard = ['', '', '', '', '', '', '', '', ''];
let gameEnded = false;

function drawBoard() {
  board.innerHTML = '';
  gameBoard.forEach((cell, index) => {
    const cellElement = document.createElement('div');
    cellElement.classList.add('cell');

    const imgElement = document.createElement('img');
    imgElement.src = cell === 'X' ? 'pictures/krizek.png' : (cell === 'O' ? 'pictures/kolo.png' : '');
    imgElement.classList.add('playerIMG');
    cellElement.appendChild(imgElement);

    cellElement.addEventListener('click', () => cellClick(index));
    board.appendChild(cellElement);
  });
}

function cellClick(index) {
  if (gameBoard[index] === '' && currentPlayer === 'X') {
    gameBoard[index] = currentPlayer;
    drawBoard();
    currentPlayer = 'O';
    checkWinner();
    if (!gameEnded) {
        botMove();
      }
  }
}

function botMove() {
  let availableMoves = gameBoard.reduce((acc, cell, index) => {
    if (cell === '') acc.push(index);
    return acc;
  }, []);

  if (!gameEnded && availableMoves.length > 0) {
    const randomIndex = Math.floor(Math.random() * availableMoves.length);
    const botIndex = availableMoves[randomIndex];

    gameBoard[botIndex] = 'O';
    drawBoard();
    currentPlayer = 'X';
    checkWinner();
  }
}

function showAftergame(message) {
  const aftergame = document.getElementById('after-game');
  const messageDiv = document.getElementById('message');
  messageDiv.innerText = message;

  const gameValue = document.querySelector('.game');
  const gameRect = gameValue.getBoundingClientRect();

  aftergame.style.width = `${gameRect.width}px`;
  aftergame.style.height = `${gameRect.height}px`;
  aftergame.style.top = `${gameRect.top}px`;
  aftergame.style.left = `${gameRect.left}px`;

  aftergame.style.display = 'flex';

  const btn1 = document.querySelector('.btns');
  btn1.style.visibility = 'hidden';
  const gameName = document.getElementById('gameName');
  gameName.style.visibility = 'hidden';
}

function hideAftergame() {
const aftergame = document.getElementById('after-game');
aftergame.style.display = 'none';

const btn1 = document.querySelector('.btns');
btn1.style.visibility = 'visible';
const gameName = document.getElementById('gameName');
gameName.style.visibility = 'visible';
}

function markWinningCells(cells) {
cells.forEach(cellIndex => {
const cellElement = document.querySelector(`.cell:nth-child(${cellIndex + 1})`);
cellElement.style.backgroundColor = 'green';
});
}

function checkWinner() {
  const winningConditions = [
[0, 1, 2],
[3, 4, 5],
[6, 7, 8],
[0, 3, 6],
[1, 4, 7],
[2, 5, 8],
[0, 4, 8],
[2, 4, 6],
]; //všechny možné pozice na výhru

let winner = null;

for (const condition of winningConditions) {
  const [a, b, c] = condition;
  if (gameBoard[a] !== '' && gameBoard[a] === gameBoard[b] && gameBoard[a] === gameBoard[c]) {
    winner = gameBoard[a];
    markWinningCells([a, b, c]);
    break;
  }
}

if (winner === 'X') {
    gameEnded = true;
    showAftergame('Výhra');
    updateScore();
  } else if (winner === 'O') {
    gameEnded = true;
    showAftergame('Prohra');
    updateBotScore();
  } else if (!gameBoard.includes('')) {
    gameEnded = true;
    showAftergame('Remíza');
  }
}

function resetBoard() {
  gameBoard = ['', '', '', '', '', '', '', '', ''];
  currentPlayer = 'X';
  gameEnded = false;
  drawBoard();
  hideAftergame();
}

function updateScore() {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "update-score.php", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var scoreDiv = document.getElementById('playerscore');
      scoreDiv.innerHTML = xhr.responseText;
    }
  };

  xhr.send("nickname=" + encodeURIComponent('<?php echo $nickname; ?>'));
}

function updateBotScore() {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "update-botscore.php", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var scoreDiv = document.getElementById('botscore');
      scoreDiv.innerHTML = xhr.responseText;
    }
  };

  xhr.send("nickname=" + encodeURIComponent('<?php echo $nickname; ?>'));
}

function loadScore() {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "load-score.php", true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var scores = xhr.responseText.split(',');
      var scoreDiv = document.getElementById('playerscore');
      var botscoreDiv = document.getElementById('botscore');

      scoreDiv.innerHTML = scores[0];
      if (scores[1] !== '') {
        botscoreDiv.innerHTML = ':' + scores[1];
      } else {
        botscoreDiv.innerHTML = '';
      }
    }
  };

  xhr.send("nickname=" + encodeURIComponent('<?php echo $nickname; ?>'));
}
loadScore();
drawBoard();