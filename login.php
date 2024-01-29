<!DOCTYPE html>
<html>
<head>
    <title>P&rcaron;ihl&aacute;&scaron;en&iacute;</title>
    <link rel="stylesheet" href="login-styl.css">
</head>
<body>
    <div class="form-container">       
        <h2>P&rcaron;ihl&aacute;&scaron;en&iacute;</h2>
        <form action="login.php" method="post">
            <input type="text" id="nickname" name="nickname" placeholder="Nickname" required class="input-field">      
            <input type="password" id="password" name="password" placeholder="Password" required class="input-field">
            <input type="submit" value="P&rcaron;ihl&aacute;sit se" class="submit-btn">
        </form>
            <p><a href="register.php">Je&scaron;t&ecaron; nem&aacute;te &uacute;&ccaron;et?</a></p>
            <p><a href="login.php?loginAsHost">Přihlásit se jako Host</a></p>
    </div>
    <?php if (!empty($message)) : ?>
        <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>
</body>
</html>

<?php
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

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
            $message =  "Nesprávné heslo";
        }
    }
}
?>