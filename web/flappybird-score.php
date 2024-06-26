<?php
session_start();
$nickname = $_SESSION['nickname'];


$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'projekt';


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Připojení k databázi selhalo: " . $conn->connect_error);
}

$score = $_POST['score'];

$get_current_score_query = "
    SELECT highscore 
    FROM flappybird 
    WHERE id = (SELECT flappybird_id FROM uzivatele WHERE nickname = ?)
";
$get_current_score = $conn->prepare($get_current_score_query);
$get_current_score->bind_param("s", $nickname);
$get_current_score->execute();
$get_current_score->bind_result($current_score);
$get_current_score->fetch();
$get_current_score->close();

if ($score > $current_score) {
    $update_score_query = "
        UPDATE flappybird 
        SET highscore = ?
        WHERE id = (SELECT flappybird_id FROM uzivatele WHERE nickname = ?)
    ";
    $update_score = $conn->prepare($update_score_query);
    $update_score->bind_param("is", $score, $nickname);
    $update_score->execute();
    $update_score->close();

}

$flappybird = "SELECT u.nickname, f.highscore FROM uzivatele u 
               JOIN flappybird f ON u.flappybird_id = f.id 
               ORDER BY f.highscore DESC LIMIT 5";
$result = $conn->query($flappybird);

$flappybirdPlayers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $flappybirdPlayers[] = $row;
    }
}
echo json_encode($flappybirdPlayers);

$conn->close();
?>
