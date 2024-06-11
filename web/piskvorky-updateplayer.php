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

$update_score_query = "
  UPDATE piskvorky
  SET score = score + 1
  WHERE id = (SELECT piskvorky_id FROM uzivatele WHERE nickname = ?)
";
$update_score = $conn->prepare($update_score_query);
$update_score->bind_param("s", $nickname);
$update_score->execute();
$update_score->close();

$select_score_query = "
  SELECT piskvorky.score
  FROM uzivatele
  JOIN piskvorky ON uzivatele.piskvorky_id = piskvorky.id
  WHERE uzivatele.nickname = ?
";
$select_score = $conn->prepare($select_score_query);
$select_score->bind_param("s", $nickname);
$select_score->execute();
$select_score->bind_result($score);

if ($select_score->fetch()) {
  echo $score;
} else {
  echo '' . ',' . '';
}
$select_score->close();

$conn->close();
?>