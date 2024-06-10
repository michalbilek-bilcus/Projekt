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
  SET score = 0
  WHERE id = (SELECT piskvorky_id FROM uzivatele WHERE nickname = ?)
";

$update_score = $conn->prepare($update_score_query);
$update_score->bind_param("s", $nickname);
$update_score->execute();
$update_score->close();


$update_botscore_query = "
  UPDATE piskvorky
  SET botscore = 0
  WHERE id = (SELECT piskvorky_id FROM uzivatele WHERE nickname = ?)
";


$update_botscore = $conn->prepare($update_botscore_query);
$update_botscore->bind_param("s", $nickname);
$update_botscore->execute();
$update_botscore->close();

$conn->close();
?>
