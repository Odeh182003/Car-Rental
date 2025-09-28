<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store form data in session variables
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['flat'] = $_POST['flat'];
    $_SESSION['street'] = $_POST['street'];
    $_SESSION['city'] = $_POST['city'];
    $_SESSION['country'] = $_POST['country'];
    $_SESSION['dob'] = $_POST['dob'];
    $_SESSION['id_number'] = $_POST['id_number'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['telephone'] = $_POST['telephone'];
    $_SESSION['cc_number'] = $_POST['cc_number'];
    $_SESSION['cc_expiry'] = $_POST['cc_expiry'];
    $_SESSION['cc_name'] = $_POST['cc_name'];
    $_SESSION['cc_bank'] = $_POST['cc_bank'];

    // Redirect to the next step
    header("Location: CustomerRegister2.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Register</title>
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
<div class="container">
    <form action="CustomerRegister.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="flat">Flat/House No:</label>
        <input type="text" id="flat" name="flat" required>

        <label for="street">Street:</label>
        <input type="text" id="street" name="street" required>

        <label for="city">City:</label>
        <input type="text" id="city" name="city" required>

        <label for="country">Country:</label>
        <input type="text" id="country" name="country" required>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required>

        <label for="id_number">ID Number:</label>
        <input type="text" id="id_number" name="id_number" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="telephone">Telephone Number:</label>
        <input type="text" id="telephone" name="telephone" required>

        <label for="cc_number">Credit Card Number:</label>
        <input type="text" id="cc_number" name="cc_number" required>

        <label for="cc_expiry">Credit Card Expiry:</label>
        <input type="date" id="cc_expiry" name="cc_expiry" required>

        <label for="cc_name">Credit Card Name:</label>
        <input type="text" id="cc_name" name="cc_name" required>

        <label for="cc_bank">Credit Card Bank:</label>
        <input type="text" id="cc_bank" name="cc_bank" required>

        <input type="submit" value="Next">
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
