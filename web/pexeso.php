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

// fruitxeso
$fruitxeso = "SELECT u.nickname, f.time FROM uzivatele u 
                JOIN pexeso f ON u.pexeso_id = f.id 
                WHERE f.time > 0
                ORDER BY f.time ASC LIMIT 5";
$result = $conn->query($fruitxeso);

$fruitxesoPlayers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fruitxesoPlayers[] = $row;
    }
}

$conn->close();
?>

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
                <?php foreach ($fruitxesoPlayers as $player) : ?>
                    <li><?php echo htmlspecialchars($player['nickname']); ?>: <?php echo htmlspecialchars($player['time']); ?> s</li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="buttons">      
            <button id="main-menu-btn" onclick="location.href='mainmenu.php';">Hlavní menu</button>
            <button onclick="resetGame()">Odznova</button>
            <div id="attempts-left">Pokusy: 10</div>
            <div id="timer">Čas: 0</div>
            <div id="playerscore"></div>
        </div>
        <div id="game-board"></div>
    </div>
    <div class="overlay" id="game-over-overlay">
        <div class="game-result">
            <p id="game-over-message"></p>
            <button id="restart-button">Odznova</button>
        </div>
    </div>
    <script src="pexeso.js"></script>
</body>
</html>
