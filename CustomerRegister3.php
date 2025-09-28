<?php
session_start();
include "db.inc.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo = db_connect();
        
        // Generate customer ID
        $customer_id = str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
        
        // Insert customer information into database
        $stmt = $pdo->prepare("INSERT INTO customers (customer_id, name, flat, street, city, country, dob, id_number, email, telephone, cc_number, cc_expiry, cc_name, cc_bank) 
                               VALUES (:customer_id, :name, :flat, :street, :city, :country, :dob, :id_number, :email, :telephone, :cc_number, :cc_expiry, :cc_name, :cc_bank)");
        $stmt->bindValue(':customer_id', $customer_id);
        $stmt->bindValue(':name', $_SESSION['name']);
        $stmt->bindValue(':flat', $_SESSION['flat']);
        $stmt->bindValue(':street', $_SESSION['street']);
        $stmt->bindValue(':city', $_SESSION['city']);
        $stmt->bindValue(':country', $_SESSION['country']);
        $stmt->bindValue(':dob', $_SESSION['dob']);
        $stmt->bindValue(':id_number', $_SESSION['id_number']);
        $stmt->bindValue(':email', $_SESSION['email']);
        $stmt->bindValue(':telephone', $_SESSION['telephone']);
        $stmt->bindValue(':cc_number', $_SESSION['cc_number']);
        $stmt->bindValue(':cc_expiry', $_SESSION['cc_expiry']);
        $stmt->bindValue(':cc_name', $_SESSION['cc_name']);
        $stmt->bindValue(':cc_bank', $_SESSION['cc_bank']);

        if ($stmt->execute()) {
            // Insert user information into user_tablename
            $stmt = $pdo->prepare("INSERT INTO user_tablename (username, userpassword, userposition) VALUES (:username, :userpassword, :userposition)");
            $stmt->execute([
                ':username' => $_SESSION['username'],
                ':userpassword' => $_SESSION['userpassword'],
                ':userposition' => 'Customer'
            ]);
            
            // Clear session variables after successful registration
            session_unset();
            session_destroy();
            
            // Redirect to success page with customer_id as URL parameter
            header("Location: registrationSuccess.php?customer_id=$customer_id");
            exit();
        } else {
            throw new Exception("Failed to register customer.");
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    $pdo = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Registration - Step 3</title>
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
    <h2>Customer Registration - Step 3</h2>
    <div class="container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <h3>Review your information:</h3>
        <p>Name: <?php echo $_SESSION['name']; ?></p>
        <p>Address: <?php echo $_SESSION['flat'] . ", " . $_SESSION['street'] . ", " . $_SESSION['city'] . ", " . $_SESSION['country']; ?></p>
        <p>Date of Birth: <?php echo $_SESSION['dob']; ?></p>
        <p>ID Number: <?php echo $_SESSION['id_number']; ?></p>
        <p>Email: <?php echo $_SESSION['email']; ?></p>
        <p>Telephone: <?php echo $_SESSION['telephone']; ?></p>
        <p>Credit Card Number: <?php echo $_SESSION['cc_number']; ?></p>
        <p>Credit Card Expiry Date: <?php echo $_SESSION['cc_expiry']; ?></p>
        <p>Credit Card Holder Name: <?php echo $_SESSION['cc_name']; ?></p>
        <p>Credit Card Issuing Bank: <?php echo $_SESSION['cc_bank']; ?></p>
        <p>Username: <?php echo $_SESSION['username']; ?></p>
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
