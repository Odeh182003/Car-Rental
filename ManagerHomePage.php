<?php
session_start();
?>
<html>
    <head>
        <title>Manager Home Page</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
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
            <h1>Manager Home Page</h1><br><br>
            <div class="nav">
                <a href="addCar.php">Add a Car</a>
                <a href="manager_returns.php">Return A Car</a>
                <a href="carInquire.php"> Cars Inquire</a>
                <a href="addLocation.php">add a new Location</a>
                
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