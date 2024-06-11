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

// fastclick
$fastclick = "SELECT u.nickname, f.highscore FROM uzivatele u 
        JOIN klikacka f ON u.klikacka_id = f.id 
        ORDER BY f.highscore DESC LIMIT 5";
$result = $conn->query($fastclick);

$fastclickPlayers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fastclickPlayers[] = $row;
    }
}

$conn->close();
?>

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
                <?php foreach ($fastclickPlayers as $player) : ?>
                    <li><?php echo htmlspecialchars($player['nickname']); ?>: <?php echo htmlspecialchars($player['highscore']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
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
