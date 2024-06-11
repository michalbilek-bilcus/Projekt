<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projekt";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Připojení k databázi selhalo: " . $conn->connect_error);
}

$message = "";

if (isset($_GET['loginAsHost'])) {
    session_start();
    $_SESSION['nickname'] = 'host';
    header("Location: mainmenu.php");
    exit();
}

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
    } elseif ($password_length < 8 || !$uppercase || !$number) {
        $message = "Heslo musí mít minimálně 8 znaků a obsahovat alespoň jedno velké písmeno a jedno číslo.";    
    } elseif ($password !== $confirm_password) {
        $message = "Hesla se neshodují";
    } else {
        $insert_query = $conn->prepare("INSERT INTO uzivatele (nickname, password) VALUES (?, ?)");
        $insert_query->bind_param("ss", $nickname, password_hash($password, PASSWORD_DEFAULT));

        if ($insert_query->execute()) {
            $user_id = $insert_query->insert_id;

            //chytani předmětů
            $insert_chytanipredmetu = $conn->prepare("INSERT INTO chytanipredmetu (highscore) VALUES (0)");
            $insert_chytanipredmetu->execute();
            $chytanipredmetu_id = $insert_chytanipredmetu->insert_id;
    
            $update_user_chytanipredmetu_id = $conn->prepare("UPDATE uzivatele SET chytanipredmetu_id = ? WHERE id = ?");
            $update_user_chytanipredmetu_id->bind_param("ii", $chytanipredmetu_id, $user_id);
            $update_user_chytanipredmetu_id->execute();

            //flappybird
            $insert_flappybird = $conn->prepare("INSERT INTO flappybird (highscore) VALUES (0)");
            $insert_flappybird->execute();
            $flappybird_id = $insert_flappybird->insert_id;
    
            $update_user_flappybird_id = $conn->prepare("UPDATE uzivatele SET flappybird_id = ? WHERE id = ?");
            $update_user_flappybird_id->bind_param("ii", $flappybird_id, $user_id);
            $update_user_flappybird_id->execute();

            //hledani min
            $insert_hledanimin = $conn->prepare("INSERT INTO hledanimin (time) VALUES (NULL)");
            $insert_hledanimin->execute();
            $hledanimin_id = $insert_hledanimin->insert_id;
    
            $update_user_hledanimin_id = $conn->prepare("UPDATE uzivatele SET hledanimin_id = ? WHERE id = ?");
            $update_user_hledanimin_id->bind_param("ii", $hledanimin_id, $user_id);
            $update_user_hledanimin_id->execute();

            //klikacka
            $insert_klikacka = $conn->prepare("INSERT INTO klikacka (highscore) VALUES (0)");
            $insert_klikacka->execute();
            $klikacka_id = $insert_klikacka->insert_id;
    
            $update_user_klikacka_id = $conn->prepare("UPDATE uzivatele SET klikacka_id = ? WHERE id = ?");
            $update_user_klikacka_id->bind_param("ii", $klikacka_id, $user_id);
            $update_user_klikacka_id->execute();
            
            //pexeso
            $insert_pexeso = $conn->prepare("INSERT INTO pexeso (time) VALUES (NULL)");
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
    <link rel="stylesheet" href="register.css">
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
