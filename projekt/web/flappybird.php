<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flappy Bird</title>
    <link rel="stylesheet" href="flappybird.css">
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
            <div class="game">
                <div id="gameOver">
                    <p id="playerscore"></p>
                    <button onclick="restartGame()">Začít</button>
                    <button onclick="location.href='mainmenu.php'">Hlavní menu</button>
                </div>
            </div>
        </div>
    </div>
    <script src="flappybird.js"></script>
</body>
</html>
