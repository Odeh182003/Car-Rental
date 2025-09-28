<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db.inc.php";

$PHP_SELF = $_SERVER['PHP_SELF'];
do_authentication();

function do_authentication() {
    global $PHP_SELF;

    if (!isset($_POST['username'])) {
        login_form();
        exit;
    } else {
        $_SESSION['userpassword'] = $_POST['userpassword'];
        $_SESSION['username'] = $_POST['username'];
        $userid = $_POST['username'];
        $userpassword = $_POST['userpassword'];
        $redirectUrl = $_POST['redirect'] ?? 'CustomerHomePage.php';

        $pdo = db_connect();
        if (!$pdo) {
            error_message("Null PDO Object");
            exit;
        }

        if (!is_object($pdo)) {
            error_message("PDO object is not valid.");
            exit;
        }

        $query = "SELECT COUNT(*) FROM user_tablename WHERE username = :username AND userpassword = :userpassword";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':username', $userid);
        $stmt->bindValue(':userpassword', $userpassword);
        $stmt->execute();

        if ($stmt->fetchColumn() == 0) {
            unset($_SESSION['username']);
            unset($_SESSION['userpassword']);
            echo "Authorization failed. You must enter a valid username and password combo. ";
            echo "Click on the following link to try again.<br>\n";
            echo "<a href=\"$PHP_SELF\">Login</a><br>";
            echo "If you're not a member yet, click on the following link to register.<br>\n";
            echo "<a href=\"register.php\">Membership</a>";
            exit;
        } else {
            $_SESSION['logged_in'] = true;
            $_SESSION['visits'] = 0;

            $query = "SELECT userposition FROM user_tablename WHERE username = :username";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':username', $userid);
            $stmt->execute();
            $pos = $stmt->fetch()['userposition'];

            if ($pos == 'Customer') {
                $_SESSION['type'] = 1;
                header("Location: $redirectUrl");
            } elseif ($pos == 'Manager') {
                $_SESSION['type'] = 2;
                header("Location: ManagerHomePage.php");
            } 
            exit;
        }
    }
}

function login_form() {
    global $PHP_SELF;
    $redirect = $_GET['redirect'] ?? 'CustomerHomePage.php';
    ?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Styles.css">
    <title>Login Page</title>
</head>
<body>
<div class="hero">
            <nav>
                <div class="menu">
                    <a href="../index.html">Home</a>
                    <a href="Search.php">Search</a>
                    <a href="../Contact_Information.html">Contact</a>
                    <a href="AboutUs.html">About Us</a>
                </div>
            </nav>
        </div>
<header>
        <div class="header-left">
            <img src="img/carlogo.JPG" alt="Agency Logo" class="logo">
            <h1 class="agency-name">Luxury Car Rental Agency</h1>
            <a href="AboutUs.html" class="about-us">About Us</a>
        </div>
        <div class="header-right">
            
            <div class="header-links">
                <a href="ModifyProfile.php" class="user-profile">Profile</a>
                <a href="#" class="shopping-basket">Shopping Basket</a>
                <a href="login.php" class="login-link">Login</a>
                <a href="logout.php" class="logout-link">Logout</a>
            </div>
        </div>
    </header>
<div class="container">
        <div class="right-bg"><h1>Login In</h1></div>
        <div class="form-container sign-in">
            <form method="POST" action="login.php">
                <input type="text" name="username" placeholder="Enter a username" required>
                <input type="password" name="userpassword" placeholder="Enter a Password" required>
                <input type="hidden" name="redirect" value="<?php echo $redirect; ?>">
                <label for="text">If it's your first time visiting, please sign in first:</label>
                <a href="CustomerRegister.php">Register</a>
                <button type="submit">Login In</button>
            </form>
        </div>
    </div>
    <footer>
    <div class="footer-content">
        <img src="img/carlogo.JPG" alt="Agency Logo" class="footer-logo">
        <p>Luxury Car Rental Agency</p>
        <span class="separator">|</span>
        <p>Address: Birzeit beside the Birzeit university</p>
        <span class="separator">|</span>
        <p>Email: support@luxurycarrental.com</p>
        <span class="separator">|</span>
        <p>Phone: +1 234 567 890</p>
        <span class="separator">|</span>
        <a href="../contactUs.html">Contact Us</a>
    </div>
</footer>
</body>
</html>

    <?php
}

function error_message($message) {
    echo "<p>Error: $message</p>";
}
?>