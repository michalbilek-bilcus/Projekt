<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MineSweeper</title>
    <link rel="stylesheet" href="minesweeper.css">
</head>
<body>
    <div class="top-bar" id="top-bar">
        <div class="user-welcome">Uživatel: <?php 
            session_start();
            $nickname = $_SESSION['nickname'];
            echo $nickname; ?></div>
        <form action="login.php" method="post">
            <input type="submit" value="Odhlásit se" name="logout" class="logout-btn">
        </form>
    </div>
    <div class="container">
        <canvas id="board"></canvas>
        <div class="buttons">
            <p id="playerscore"></p>
            <button onclick="ResetGame()">Restart Game</button>
            <button onclick="location.href='mainmenu.php'">Return to Menu</button>
        </div>
        <div id="flags-counter">Flags: <span id="flags-count">0</span></div>
        <div id="timer">Time: <span id="time-count">0</span> seconds</div>
        <div id="overlay" class="overlay">
            <div id="game-result" class="game-result"></div>
        </div>
    </div>
</body>
<script src="minesweeper.js"></script>
</html>
