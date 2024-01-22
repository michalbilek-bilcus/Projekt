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


<!DOCTYPE html>
<html>
<head>
    <title>P&rcaron;ihl&aacute;&scaron;en&iacute;</title>
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
    background-color: black;
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