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

// Ore Catcher
$orecatcher = "SELECT u.nickname, f.highscore FROM uzivatele u 
               JOIN chytanipredmetu f ON u.chytanipredmetu_id = f.id 
               ORDER BY f.highscore DESC LIMIT 5";
$result = $conn->query($orecatcher);

$orecatcherPlayers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orecatcherPlayers[] = $row;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ore catcher</title>
    <link rel="stylesheet" href="chytanipredmetu.css">
</head>
<body>
    <div class="top-bar" id="top-bar">
        <div class="user-welcome">Uživatel: <?php 
            $nickname = $_SESSION['nickname'];
            echo $nickname; ?></div>
        <form action="login.php" method="post">
            <input type="submit" value="Odhlásit se" name="logout" class="logout-btn">
        </form>
    </div>
    
    <div class="container">
    <div class="top-players">
            <h3>Top 5 hráčů</h3>
            <ul>
                <?php foreach ($orecatcherPlayers as $player) : ?>
                    <li><?php echo htmlspecialchars($player['nickname']); ?>: <?php echo htmlspecialchars($player['highscore']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="score">
            <span id="score">0</span>
        </div>
        <canvas></canvas>
        <div class="buttons">
            <div class="game">
                <div id="gameOver">
                    <p id="playerscore">0</p>
                    <button onclick="restartGame()">Začít</button>
                    <button onclick="location.href='mainmenu.php'">Hlavní menu</button>
                </div>
            </div>
        </div>
    </div>
    <script src="chytanipredmetu.js"></script>
</body>
</html>
