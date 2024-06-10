<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klikací hra</title>
    <link rel="stylesheet" href="klikacka.css">
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
            <button  onclick="location.href='mainmenu.php';">Main menu</button>
            <button id="start-button">Začít</button>
            <p>Lives: <span id="lives">3</span></p>
            <p>Score: <span id="score">0</span></p>
            <p id="playerscore"></p>
        </div>
        <div id="game-board"></div>
    </div>
    <div class="overlay" id="game-over-overlay">
        <div class="game-result">
            <p id="game-over-message"></p>
            <button id="restart-button">Odznova</button>
        </div>
    </div>
    <script src="klikacka.js"></script>
</body>
</html>
