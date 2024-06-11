<!DOCTYPE html>
<html>
<head>
    <title>Main Menu</title>
    <link rel="stylesheet" href="mainmenu.css">
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
    <div class="games-menu">
        <div class="game">
            <div class="game-title">Piškvorky
                <div class="hover-div">
                Klasická hra známá také jako Tic-Tac-Toe, ve které se dva hráči střídají v umisťování svých symbolů (křížků a koleček) na mřížku s cílem vytvořit řadu tří svých symbolů horizontálně, vertikálně nebo diagonálně.
                </div>
            </div>
            <div class="game-item" onclick="location.href='piskvorky.php';">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRNFPG9Fw1mPkQkcXc_u0EGWAOotfZV_7_pezuH9HyMsVvVyqLbdhYTQxFQHUROZhmq4Tc&usqp=CAU" alt="">
            </div>
        </div>
        <div class="game">
            <div class="game-title">Flappy Bird
                <div class="hover-div">
                Populární arkádová hra, kde hráč ovládá ptáčka letícího mezi překážkami. Hráč musí klikat na obrazovku, aby pták letěl vzhůru a vyhýbal se trubkám.
                </div>
            </div>
            <div class="game-item" onclick="location.href='flappybird.php';">
                <img src="https://wallpapers.com/images/high/flappy-bird-background-hcl96e4l81c56r54.webp" alt="">
            </div>
        </div>
        <div class="game">
            <div class="game-title">Ore catcher
                <div class="hover-div">
                Hra zaměřená na rychlost a reflexy, ve které hráč ovládá postavu, který musí chytat padající předměty. Cílem je chytit co nejvíce předmětů, aniž by hráč chytil bombu.
                </div>
            </div>
            <div class="game-item" onclick="location.href='chytanipredmetu.php';">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS5-U5_9xOC571J3-TeD3CBoXMo77JzW4ca8E1lHHNRCvorK7_F-pOd7EonUkZB9TfX0dA&usqp=CAU" alt="">
            </div>
        </div>
        <div class="game">
            <div class="game-title">Mine Sweeper
                <div class="hover-div">
                Logická hra, kde hráč odkrývá políčka na mřížce, aniž by odhalil minu. Čísla na odkrytých políčkách ukazují, kolik min se nachází v sousedních políčkách, a hráč musí pomocí těchto informací označit všechny miny.
                </div>
            </div>
            <div class="game-item" onclick="location.href='minesweeper.php';">
                <img src="https://w7.pngwing.com/pngs/337/559/png-transparent-minesweeper-computer-icons-bing-maps-video-game-mines-miscellaneous-game-angle.png" alt="">
            </div>
        </div>
        <div class="game">
            <div class="game-title">fruitxeso
                <div class="hover-div">
                Klasická paměťová hra, ve které hráč odkrývá dvojice stejných obrázků. Cílem je najít všechny páry obrázků na herní ploše s co nejmenším počtem pokusů.
                </div>
            </div>
            <div class="game-item" onclick="location.href='pexeso.php';">
                <img src="https://www.bkovarikova.cz/obj/files/3/sys_media_1988.jpg" alt="">
            </div>
        </div>
        <div class="game">
            <div class="game-title">fast click
                <div class="hover-div">
                Jednoduchá hra, kde hráč kliká na různé objekty na obrazovce, aby získal body. Cílem je kliknout co nejrychleji a co nejvícekrát v daném časovém limitu.
                </div>
            </div>
            <div class="game-item" onclick="location.href='klikacka.php';">
                <img src="data:image/png;base64,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" alt="">
            </div>
        </div>
    </div>
</body>
</html>

<?php
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