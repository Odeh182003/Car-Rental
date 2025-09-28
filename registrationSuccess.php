
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Successful</title>
    <link rel="stylesheet" href="Styles.css">
</head>
<body>
<?php
session_start();
?>
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
    <h2>Registration Successful!</h2>
    <div class="container">
    <?php
    $customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : null;
    if ($customer_id) {
        echo "Your customer ID is " . $customer_id  ."<br>"." <a  href='login.php'>Login here</a>";
    } else {
        echo "Customer ID not found.";
    }
    ?>
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
