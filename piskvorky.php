<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Piškvorky</title>
  <link rel="stylesheet" href="piskvorky-styl.css">
</head>
<body>
    <div class="game-container">
        <div class="game">
            <h1 id="gameName">Piškvorky</h1>
            <div class="board" id="board">
                <!--  pole -->
            </div>
            <div class="btns" id="btns">
                <button class="btn" id="btn1" onclick="resetBoard()">Hrát odznova</button>
                <button class="btn" id="btn1" onclick="location.href='mainmenu.php';">Domů</button>
            </div>       
        </div>
        <div class="after-game" id="after-game">
          <div class="message" id="message">
              <!-- Zde se zobrazí výsledek hry -->
          </div>
          <div class="btns">
            <button class="btn" onclick="resetBoard()">Hrát znovu</button>
            <button class="btn" onclick="location.href='mainmenu.php';">Domů</button>
          </div>         
        </div>
        <div class="score" id="score">
          <div class="scoreDiv">
              <div id="playerscore"></div>          
              <div id="botscore"></div>
          </div>
          <div id="resetBtn">
            <button class="btn" onclick="resetScore()">Restartovat skóre</button>
          </div>        
        </div>
    </div> 
    <script src="piskvorky.js"></script>
</body>
</html>