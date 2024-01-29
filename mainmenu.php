<!DOCTYPE html>
<html>
<head>
    <title>Main Menu</title>
    <link rel="stylesheet" href="mainmenu-styl.css">
</head>
<body>
    <div class="top-bar" id="top-bar">
        <div class="user-welcome">Uživatel: <?php 
            session_start();
            $nickname = $_SESSION['nickname'];
            echo $nickname; ?></div>
        <form action="mainmenu.php" method="post">
            <input type="submit" value="Odhlásit se" name="logout" class="logout-btn">
        </form>
    </div>
    <div class="games-menu">
        <div class="game">
            <div class="game-title">Piškvorky</div>
            <div class="game-item" onclick="location.href='piskvorky.php';">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRNFPG9Fw1mPkQkcXc_u0EGWAOotfZV_7_pezuH9HyMsVvVyqLbdhYTQxFQHUROZhmq4Tc&usqp=CAU" alt="">
            </div>
        </div>
        <div class="game">
            <div class="game-title">Hra 2</div>
            <div class="game-item" onclick="location.href='game2.php';">
                <!-- obrázek -->
            </div>
        </div>
        <div class="game">
            <div class="game-title">Hra 3</div>
            <div class="game-item" onclick="location.href='game3.php';">
                <!-- obrázek -->
            </div>
        </div>
        <div class="game">
            <div class="game-title">Hra 4</div>
            <div class="game-item" onclick="location.href='game4.php';">
                <!-- obrázek -->
            </div>
        </div>
        <div class="game">
            <div class="game-title">Hra 5</div>
            <div class="game-item" onclick="location.href='game5.php';">
                <!-- obrázek -->
            </div>
        </div>
        <div class="game">
            <div class="game-title">Hra 6</div>
            <div class="game-item" onclick="location.href='game6.php';">
                <!-- obrázek -->
            </div>
        </div>
    </div>

    <script>      
        var topBar = document.getElementById("top-bar");
        var lastScrollTop = 0;

    window.addEventListener("scroll", function() {
        var currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        if (currentScroll > lastScrollTop) {
            topBar.style.top = "-50px"; // Skryje top-bar
        } else {
            // Skroluje nahoru
            topBar.style.top = "0"; // Zobrazí top-bar
        }
        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // Nastaví poslední skrolování na 0, pokud je rovno nebo menší než 0
        }, false);
    </script>
</body>
</html>

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['nickname'])) {
    header("Location: login.php");
    exit();
}

$nickname = $_SESSION['nickname'];
?>