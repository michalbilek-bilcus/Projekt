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

<!DOCTYPE html>
<html>
<head>
    <title>Main Menu</title>
    <style>
   body {
        margin: 0;
        padding: 0;
        height: 100vh;
        background-image: url('https://wallpapercave.com/wp/wp11898445.jpg');
        background-size: cover;
        background-attachment: fixed;
    }

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;

        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background-color: lightgrey;
        padding: 10px;
        transition: top 0.3s ease-in-out;
    }

    .user-welcome {
        font-size: 1.2em;
        margin-right: 10px;
    }

    .logout-btn {
        padding: 5px 10px;
        background-color: black;
        color: white;
        border: none;
        cursor: pointer;
    }

    .logout-btn:hover {
        background-color: grey;
    }

    .games-menu {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        width: 100%;

        position: absolute;
        top: 50px;
        left: 0px;
    }

    .game {
        text-align: center;
        background-image: url('background/arcade.png');
        background-size: cover;
        width: 33%;
        height: 70vh;
    }

    .game-title {
        margin-top: 12%;
        font-size: 20px;
        color: white;
    }

    .game-item {
        margin-top: 13%;
        margin-left: 2.3%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .game-item img:hover {
        filter: brightness(0.3);
    }

    .game-item img {
        width: 41%;
        height: 20vh;
        transition: filter 1s ease;
    }
    </style>
</head>
<body>
    <div class="top-bar" id="top-bar">
        <div class="user-welcome">Uživatel: <?php echo $nickname; ?></div>
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