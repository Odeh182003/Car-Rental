<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate password match
    if ($_POST['userpassword'] !== $_POST['password_confirm']) {
        echo "Passwords do not match.";
        exit();
    }

    // Store form data in session variables
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['userpassword'] = $_POST['userpassword']; 
    // Redirect to the next step
    header("Location: CustomerRegister3.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Registration - Step 2</title>
    <link rel="stylesheet" href="Styles.css">
</head>
<body>
<div class="hero">
            <nav>
                <div class="menu">
                    <a href="../index.html">Home</a>
                    <a href="Search.php">Search</a>
                    <a href="../Contact_Information.html">Contact</a>
                    
                </div>
            </nav>
        </div>
<header>
            <div class="header-left">
                <img src="img\carlogo.JPG" alt="Agency Logo" class="logo">
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
<h2>Customer Registration - Step 2</h2>
<div class="container">
<form action="CustomerRegister2.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="should be between (6-13 characters)" required pattern=".{6,13}">
    <label for="password">Password:</label>
    <input type="userpassword" id="userpassword" name="userpassword" placeholder="should be between (8-12 characters)" required pattern=".{8,12}">
    <label for="password_confirm">Confirm Password:</label>
    <input type="password" id="password_confirm" name="password_confirm" required>
    <input type="submit" value="Confirm">
</form>
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
