<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'projekt';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Připojení k databázi selhalo: " . $conn->connect_error);
}
session_start();
$nickname = $_SESSION['nickname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Piškvorky</title>
  <style>

body {
  margin: 0;
  padding: 0;
  height: 100vh;
  background-image: url('https://wallpapercave.com/wp/wp11898445.jpg');
  background-size: cover;
  background-attachment: fixed;
}

.game-container {
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: none;
  padding: 20px;
  box-sizing: border-box;
}

.game {
  margin-top: 100px;
  text-align: center;
  background-color: white;
  padding: 20px 50px 20px 50px;
}

.game h1 {
  display: block;
}

.board {
  display: grid;
  grid-template-columns: repeat(3, 100px);
  grid-gap: 5px;
}

.cell {
  width: 100px;
  height: 100px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-size: cover;
  cursor: pointer;

  background-color: lightgrey;
}

.cell:hover {
    background-color: lightblue;
}

.btns {
    visibility: visible;
    margin-top: 10px;
}

.btn {
    padding: 5px 10px;
    background-color: black;
    color: white;
    border: none;
    cursor: pointer;
}

.btn:hover {
    background-color: grey;
}

.after-game {
  display: none;
  position: absolute;
  background-color: rgba(128, 128, 128, 0.6);
  text-align: center;
  justify-content: center;
  z-index: 9999;

}

.after-game .message {
  margin-top: 10px;
  font-size: 50px;
  color: black;
}

.after-game .btns {
  display: inline-block;
  position: absolute;
  top: 93%;
  left: 50%;
  transform: translate(-50%, -93%);
}
.score {
  display: flex;
  margin-top: 100px;
  text-align: center;
  justify-content: center;
  background-color: white;

  color: black;
  font-size: 50px;

  width: 20%;
  height: 465px;
  border: 3px solid black;
}

.score .btn {
  display: inline-block;
  position: absolute;
  top: 78%;
  left: 65%;
  transform: translate(-65%, -78%);
}
  </style>
</head>
<body>
    <div class="game-container">
        <div class="game">
            <h1 id="gameName">Piškvorky</h1>
            <div class="board" id="board">
                <!--  pole -->
            </div>
            <div class="btns" id="btns">
                <button class="btn" id="btn1" onclick="resetBoard()">Hrát odznova</button>
                <button class="btn" id="btn1" onclick="location.href='mainmenu.php';">Domů</button>
            </div>       
        </div>
        <div class="after-game" id="after-game">
          <div class="message" id="message">
              <!-- Zde se zobrazí výsledek hry -->
          </div>
          <div class="btns">
            <button class="btn" onclick="resetBoard()">Hrát znovu</button>
            <button class="btn" onclick="location.href='mainmenu.php';">Domů</button>
          </div>         
        </div>
        <div class="score" id="score">
          <div id="scoreDiv">
              <!-- Zde se zobrazí score piskvorek -->
          </div>
            
            <button class="btn" onclick="resetScore()">Restartovat skóre</button>
        </div>
    </div>

  <script>
    
    const board = document.getElementById('board');
    let currentPlayer = 'X';
    let gameBoard = ['', '', '', '', '', '', '', '', ''];

  function drawBoard() {
      board.innerHTML = '';
      gameBoard.forEach((cell, index) => {
        const cellElement = document.createElement('div');
        cellElement.classList.add('cell');

        const imgElement = document.createElement('img');
        imgElement.src = cell === 'X' ? 'pictures/křížek.png' : (cell === 'O' ? 'pictures/kolečko.png' : '');
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
        botMove();
      }
    }

  function botMove() {
      let availableMoves = gameBoard.reduce((acc, cell, index) => {
        if (cell === '') acc.push(index);
        return acc;
      }, []);

      if (availableMoves.length > 0) {
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

    // Získání rozměrů a pozice .game
      const gameValue = document.querySelector('.game');
      const gameRect = gameValue.getBoundingClientRect();

    // Nastavení rozměrů a pozice .after-game
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
  ]; //všechny možné scénáře na výhru

  let winner = null; //pomůže určit vítěze

    for (const condition of winningConditions) {
      const [a, b, c] = condition;
      if (gameBoard[a] !== '' && gameBoard[a] === gameBoard[b] && gameBoard[a] === gameBoard[c]) {
        winner = gameBoard[a];
        markWinningCells([a, b, c]);
        break;
      }
    }

    if (winner === 'X') {
      showAftergame('Výhra');
      
    } else if (winner === 'O') {
      showAftergame('Prohra');
    } else if (!gameBoard.includes('')) {
      showAftergame('Remíza');
    }
  }

    function resetBoard() {
      gameBoard = ['', '', '', '', '', '', '', '', ''];
      currentPlayer = 'X';
      drawBoard();
      hideAftergame();
    }

    drawBoard();
  </script>
</body>
</html>

<?php
$select_score_query = "
SELECT piskvorky.score
FROM uzivatele
JOIN piskvorky ON uzivatele.piskvorky_id = piskvorky.id
WHERE uzivatele.nickname = ?
";

$select_score_statement = $conn->prepare($select_score_query);
$select_score_statement->bind_param("s", $nickname);
$select_score_statement->execute();
$select_score_statement->bind_result($score);

if ($select_score_statement->fetch()) {
echo "<script>
var scoreDiv = document.getElementById('score');
scoreDiv.innerHTML = 'Nickname: " . $nickname . " - Highscore: " . $score . "';
</script>";
} else {
echo "<script>
var scoreDiv = document.getElementById('scoreDiv');
scoreDiv.innerHTML = '$nickname';
</script>";
}

$select_score_statement->close();
$conn->close();
?>