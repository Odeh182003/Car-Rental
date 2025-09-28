<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$rentDetails = $_SESSION['rent_details'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Finalize Rental</title>
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
                <div class="user-info">
                    <span class="user-name">User Name</span>
                    <span class="username"><?php echo $_SESSION['username']?></span>
                </div>
                <div class="header-links">
                    <a href="ModifyProfile.php" class="user-profile">Profile</a>
                    <a href="#" class="shopping-basket">Shopping Basket</a>
                    <a href="login.php" class="login-link">Login</a>
                    <a href="logout.php" class="logout-link">Logout</a>
                </div>
            </div>
        </header>
    <div class="container">
        <h1>Finalize Rental</h1>
        
        <form action="confirm_rent.php" method="POST">
        <span>
        <label>Total Cost:</label>
        <input type="text" name="total_cost" value="<?php echo $rentDetails['total_cost']; ?>" readonly>
        </span>
                
           <span>
           <label>Card Number:</label>
           <input type="text" name="card_number" maxlength="9" required>
           </span>
                
            <span>
                <label>Expiration Date:</label>
                <input type="date" name="expiration_date" required>
            </span>
            <span>
                <label>Card Holder Name:</label>
                <input type="text" name="card_holder" required>
            </span>
            <span>
                <label>Bank Issued:</label>
                <input type="text" name="bank_issued" required>
            </span>
            <span>
                <label>Card Type:</label>
                <input type="radio" name="card_type" value="Visa" required> Visa 
                <input type="radio" name="card_type" value="Master Card" required> Master Card
            </span>
            <span>
                <label>Accept Terms and Conditions:</label>
                <input type="checkbox" name="accept_terms" value="1" required>
            </span>
            <span>
                <label>Signature:</label>
                <input type="text" name="signature" required>
            </span>
            <span>
                <label>Date:</label>
                <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" readonly>
            </span>
            <button type="submit">Confirm Rent</button>
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
