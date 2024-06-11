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

$update_botscore_query = "
  UPDATE piskvorky
  SET botscore = botscore + 1
  WHERE id = (SELECT piskvorky_id FROM uzivatele WHERE nickname = ?)
";
$update_botscore = $conn->prepare($update_botscore_query);
$update_botscore->bind_param("s", $nickname);
$update_botscore->execute();
$update_botscore->close();

$select_botscore_query = "
  SELECT piskvorky.botscore
  FROM uzivatele
  JOIN piskvorky ON uzivatele.piskvorky_id = piskvorky.id
  WHERE uzivatele.nickname = ?
";
$select_botscore = $conn->prepare($select_botscore_query);
$select_botscore->bind_param("s", $nickname);
$select_botscore->execute();
$select_botscore->bind_result($botscore);

if ($select_botscore->fetch()) {
  echo $botscore;
} else {
    echo '' . ',' . '';
}
$select_botscore->close();

$conn->close();
?>