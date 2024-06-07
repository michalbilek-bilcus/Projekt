<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pexeso</title>
    <link rel="stylesheet" href="pexeso.css">
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
        <div class="buttons">
        <button onclick="resetGame()">Restart</button>
            <button id="main-menu-btn" onclick="location.href='mainmenu.php';">Main menu</button>
            <div id="attempts-left">Tries: 10</div>
            <div id="timer">Time: 0</div>
            <div id="playerscore"></div>
        </div>
        <div id="game-board"></div>
    </div>
    <div class="overlay" id="game-over-overlay">
        <div class="game-result">
            <p id="game-over-message"></p>
            <button id="restart-button">Restart</button>
        </div>
    </div>
    <script src="pexeso.js"></script>
</body>
</html>
