<?php 
      session_start();
      $nickname = $_SESSION['nickname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Piškvorky</title>
  <link rel="stylesheet" href="piskvorky.css">
  <script>
    var nickname = <?php echo json_encode($nickname); ?>;
  </script>
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
    <div class="game-container">
        <div class="game">
            <h1 id="gameName">Piškvorky</h1>
            <div class="board" id="board">
            </div>
            <div class="btns" id="btns">
                <button class="btn" id="btn1" onclick="resetBoard()">New try</button>
                <button class="btn" id="btn1" onclick="location.href='mainmenu.php';">Hlavní menu</button>
            </div>       
        </div>
        <div class="after-game" id="after-game">
          <div class="message" id="message">
          </div>
          <div class="btns">
            <button class="btn" onclick="resetBoard()">Odznova</button>
            <button class="btn" onclick="location.href='mainmenu.php';">Hlavní manu</button>
          </div>         
        </div>
        <div class="score" id="score">
          <div class="scoreDiv">
              <div id="playerscore"></div>          
              <div id="botscore"></div>
          </div>
          <div id="resetBtn">
            <button class="btn" onclick="resetScore()">Vynulovat skóre</button>
          </div>        
        </div>
    </div> 
    <script src="piskvorky.js"></script>
</body>
</html>