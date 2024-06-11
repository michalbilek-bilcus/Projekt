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

// Minesweeper
$minesweeper = "SELECT u.nickname, f.time FROM uzivatele u 
                JOIN hledanimin f ON u.hledanimin_id = f.id 
                WHERE f.time > 0
                ORDER BY f.time ASC LIMIT 5";
$result = $conn->query($minesweeper);

$minesweeperPlayers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $minesweeperPlayers[] = $row;
    }
}

$conn->close();
?>
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
                <?php foreach ($minesweeperPlayers as $player) : ?>
                    <li><?php echo htmlspecialchars($player['nickname']); ?>: <?php echo htmlspecialchars($player['time']); ?> s</li>
                <?php endforeach; ?>
            </ul>
        </div>
        <canvas id="board"></canvas>
        <div class="buttons">
            <p id="playerscore"></p>
            <button onclick="ResetGame()">Odznova</button>
            <button onclick="location.href='mainmenu.php'">Hlavní menu</button>
        </div>
        <div id="flags-counter">Vlajky: <span id="flags-count">0</span></div>
        <div id="timer">Čas: <span id="time-count">0</span> sekund</div>
        <div id="overlay" class="overlay">
            <div id="game-result" class="game-result"></div>
        </div>
    </div>
</body>
<script src="minesweeper.js"></script>
</html>
