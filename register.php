<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projekt";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Připojení k databázi selhalo: " . $conn->connect_error);
}

if (isset($_GET['loginAsHost'])) {
    session_start();
    $_SESSION['nickname'] = 'host';
    header("Location: mainmenu.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nickname = $_POST['nickname'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    $check_query = "SELECT id FROM uzivatele WHERE nickname = '$nickname'";
    $result = $conn->query($check_query);

    $password_length = strlen($password);
    $uppercase = preg_match('@[A-Z]@', $password);
    $number = preg_match('@[0-9]@', $password);

    if ($result->num_rows > 0) {
        $message = "Tato přezdívka je již obsazená.";
    } else if ($password_length < 8 || !$uppercase || !$number) {
        $message = "Heslo musí mít minimálně 8 znaků a obsahovat alespoň jedno velké písmeno a jedno číslo.";    
    } else if ($password !== $confirm_password) {
        $message = "Hesla se neshodují";
    } else {
        $insert_query = $conn->prepare("INSERT INTO uzivatele (nickname, password) VALUES (?, ?)");
        $insert_query->bind_param("ss", $nickname, password_hash($password, PASSWORD_DEFAULT));

        if ($insert_query->execute()) {
            $user_id = $insert_query->insert_id;

            //chytani předmětů
            $insert_chytanipredmetu = $conn->prepare("INSERT INTO chytanipredmetu (highscore, time) VALUES (0, 0)");
            $insert_chytanipredmetu->execute();
            $chytanipredmetu_id = $insert_chytanipredmetu->insert_id;
    
            $update_user_chytanipredmetu_id = $conn->prepare("UPDATE uzivatele SET chytanipredmetu_id = ? WHERE id = ?");
            $update_user_chytanipredmetu_id->bind_param("ii", $chytanipredmetu_id, $user_id);
            $update_user_chytanipredmetu_id->execute();

            //flappybird
            $insert_flappybird = $conn->prepare("INSERT INTO flappybird (highscore, time) VALUES (0, 0)");
            $insert_flappybird->execute();
            $flappybird_id = $insert_flappybird->insert_id;
    
            $update_user_flappybird_id = $conn->prepare("UPDATE uzivatele SET flappybird_id = ? WHERE id = ?");
            $update_user_flappybird_id->bind_param("ii", $flappybird_id, $user_id);
            $update_user_flappybird_id->execute();

            //hledani min
            $insert_hledanimin = $conn->prepare("INSERT INTO hledanimin (highscore, time) VALUES (0, 0)");
            $insert_hledanimin->execute();
            $hledanimin_id = $insert_hledanimin->insert_id;
    
            $update_user_hledanimin_id = $conn->prepare("UPDATE uzivatele SET hledanimin_id = ? WHERE id = ?");
            $update_user_hledanimin_id->bind_param("ii", $hledanimin_id, $user_id);
            $update_user_hledanimin_id->execute();

            //klikacka
            $insert_klikacka = $conn->prepare("INSERT INTO klikacka (highscore, time) VALUES (0, 0)");
            $insert_klikacka->execute();
            $klikacka_id = $insert_klikacka->insert_id;
    
            $update_user_klikacka_id = $conn->prepare("UPDATE uzivatele SET klikacka_id = ? WHERE id = ?");
            $update_user_klikacka_id->bind_param("ii", $klikacka_id, $user_id);
            $update_user_klikacka_id->execute();
            
            //pexeso
            $insert_pexeso = $conn->prepare("INSERT INTO pexeso (highscore, time) VALUES (0, 0)");
            $insert_pexeso->execute();
            $pexeso_id = $insert_pexeso->insert_id;
    
            $update_user_pexeso_id = $conn->prepare("UPDATE uzivatele SET pexeso_id = ? WHERE id = ?");
            $update_user_pexeso_id->bind_param("ii", $pexeso_id, $user_id);
            $update_user_pexeso_id->execute();

            //piskvorky
            $insert_piskvorky = $conn->prepare("INSERT INTO piskvorky (score, botscore) VALUES (0,0)");
            $insert_piskvorky->execute();
            $piskvorky_id = $insert_piskvorky->insert_id;
    
            $update_user_piskvorky_id = $conn->prepare("UPDATE uzivatele SET piskvorky_id = ? WHERE id = ?");
            $update_user_piskvorky_id->bind_param("ii", $piskvorky_id, $user_id);
            $update_user_piskvorky_id->execute();

            header("Location: login.php");
            exit();
        } else {
            $message = "Chyba při registraci: " . $conn->error;
}
$insert_query->close();

    }
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registrace</title>
    <style>
        body {
    margin: 0;
    padding: 0;
    display: block;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-image: url('https://e0.pxfuel.com/wallpapers/416/755/desktop-wallpaper-minimal-cabinet-action-games.jpg');
    background-size: cover;
    background-position-y: -400px;
}

.form-container {
    width: 300px;
    padding: 20px;
    border-radius: 5px;
    background-color: black;
    box-shadow: black;
    position: absolute;
    margin-top: 150px;
    margin-left: 200px;
}

.form-container h2, p, a {
    text-align: center;
    color: #ffffff;
}

.form-container input[type="text"],
.form-container input[type="password"] {
    width: 95%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 3px;
    border: 1px solid #ccc;
    background-color:black;
    color: white;
}

.form-container input[type="submit"] {
    width: 102%;
    padding: 10px;
    margin-top: 10px;
    border: 1px;
    border-radius: 3px;
    background-color: white;
    color: black;
    cursor: pointer;
}

.form-container input[type="submit"]:hover {
    background-color: grey;
}

.form-container p, a:hover {
    color: grey;
}

.error-message {
    color: red;
    position: absolute;
    margin-top: 300px;
    margin-left: 600px;
    width: 300px;
    word-wrap: break-word;
}
    </style>
</head>
<body>   
    <div class="form-container">
        <h2>Registrace</h2>
        <form action="register.php" method="post">
            <input type="text" id="nickname" name="nickname" placeholder="Nickname" required class="input-field">
            <input type="password" id="password" name="password" placeholder="Password" required class="input-field">
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm password" required class="input-field">
            <input type="submit" value="Register" class="submit-btn">
        </form>
            <p><a href="login.php" class="form-link">Máš už účet?</a></p>
            <p><a href="register.php?loginAsHost" class="form-link">Přihlásit se jako Host</a></p>
    </div>
    <?php if (!empty($message)) : ?>
        <p class="error-message"><?php echo $message; ?></p>
    <?php endif; ?>
</body>
</html>
