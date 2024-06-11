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

$select_score_query = "
  SELECT chytanipredmetu.highscore
  FROM uzivatele
  JOIN chytanipredmetu ON uzivatele.chytanipredmetu_id = chytanipredmetu.id
  WHERE uzivatele.nickname = ?
";
$select_score = $conn->prepare($select_score_query);
$select_score->bind_param("s", $nickname);
$select_score->execute();
$select_score->bind_result($score);

if ($select_score->fetch()) {
  echo $score;
} else {
    echo '';
}
$select_score->close();
$conn->close();
?>
