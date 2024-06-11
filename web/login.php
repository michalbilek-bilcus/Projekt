<?php
$message = "";

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'projekt';

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

$form_submitted = isset($_POST['form_submitted']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && $form_submitted) {
    if (isset($_POST['nickname']) && isset($_POST['password'])) {
        $nickname = $_POST['nickname'];
        $password = $_POST['password'];

        $sql_password = "SELECT password FROM uzivatele WHERE nickname = ?";
        $stmt = $conn->prepare($sql_password);
        $stmt->bind_param("s", $nickname);
        $stmt->execute();
        $result_password = $stmt->get_result();

        if ($result_password->num_rows === 0) {
            $message = "Tento účet není v naší databázi";
        } else {
            $row = $result_password->fetch_assoc();
            $stored_password = $row['password'];

            if (password_verify($password, $stored_password)) {
                session_start();
                $_SESSION['nickname'] = $nickname;
                header("Location: mainmenu.php");
                exit();
            } else {            
                $message = "špatné heslo";
            }
        }
    } else {
        $message = "Prosím vyplňte všechna pole";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Přihlášení</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="form-container">       
        <h2>Přihlášení</h2>
        <form action="login.php" method="post">
            <input type="text" id="nickname" name="nickname" placeholder="Nickname" required class="input-field">      
            <input type="password" id="password" name="password" placeholder="Password" required class="input-field">
            <input type="hidden" name="form_submitted" value="1">
            <input type="submit" value="Přihlásit se" class="submit-btn">
        </form>
        <p><a href="register.php">Ještě nemáte účet?</a></p>
        <p><a href="login.php?loginAsHost">Přihlásit se jako Host</a></p>
        
    </div>
    <?php if (!empty($message)) : ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>
</body>
</html>
