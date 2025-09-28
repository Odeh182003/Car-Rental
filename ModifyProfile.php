<?php

include "db.inc.php";
session_start();

// Check if the customer is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

try {
    $pdo = db_connect();
    
    // Fetch customer information from the database using the username from user_tablename
    $stmt = $pdo->prepare("
        SELECT c.* 
        FROM customers c 
        JOIN user_tablename u ON c.name = u.username 
        WHERE u.username = :username
    ");
    $stmt->bindValue(':username', $_SESSION['username']);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        throw new Exception("Customer not found.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Update customer information in the database
        $stmt = $pdo->prepare("
            UPDATE customers 
            SET name = :name, flat = :flat, street = :street, city = :city, country = :country, dob = :dob, id_number = :id_number, email = :email, telephone = :telephone, cc_number = :cc_number, cc_expiry = :cc_expiry, cc_name = :cc_name, cc_bank = :cc_bank 
            WHERE customer_id = :customer_id
        ");
        $stmt->bindValue(':name', $_POST['name']);
        $stmt->bindValue(':flat', $_POST['flat']);
        $stmt->bindValue(':street', $_POST['street']);
        $stmt->bindValue(':city', $_POST['city']);
        $stmt->bindValue(':country', $_POST['country']);
        $stmt->bindValue(':dob', $_POST['dob']);
        $stmt->bindValue(':id_number', $_POST['id_number']);
        $stmt->bindValue(':email', $_POST['email']);
        $stmt->bindValue(':telephone', $_POST['telephone']);
        $stmt->bindValue(':cc_number', $_POST['cc_number']);
        $stmt->bindValue(':cc_expiry', $_POST['cc_expiry']);
        $stmt->bindValue(':cc_name', $_POST['cc_name']);
        $stmt->bindValue(':cc_bank', $_POST['cc_bank']);
        $stmt->bindValue(':customer_id', $customer['customer_id']);

        if ($stmt->execute()) {
            echo "Profile updated successfully!";
        } else {
            echo "Failed to update profile.";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Profile</title>
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
    <h2>Customer Profile</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <p>Customer ID: <input type="text" name="customer_id" value="<?php echo $customer['customer_id']; ?>" readonly></p>
        <p>Name: <input type="text" name="name" value="<?php echo $customer['name']; ?>"></p>
        <p>Flat: <input type="text" name="flat" value="<?php echo $customer['flat']; ?>"></p>
        <p>Street: <input type="text" name="street" value="<?php echo $customer['street']; ?>"></p>
        <p>City: <input type="text" name="city" value="<?php echo $customer['city']; ?>"></p>
        <p>Country: <input type="text" name="country" value="<?php echo $customer['country']; ?>"></p>
        <p>Date of Birth: <input type="date" name="dob" value="<?php echo $customer['dob']; ?>"></p>
        <p>ID Number: <input type="text" name="id_number" value="<?php echo $customer['id_number']; ?>"></p>
        <p>Email: <input type="email" name="email" value="<?php echo $customer['email']; ?>"></p>
        <p>Telephone: <input type="text" name="telephone" value="<?php echo $customer['telephone']; ?>"></p>
        <p>Credit Card Number: <input type="text" name="cc_number" value="<?php echo $customer['cc_number']; ?>"></p>
        <p>Credit Card Expiry Date: <input type="year" name="cc_expiry" value="<?php echo $customer['cc_expiry']; ?>"></p>
        <p>Credit Card Holder Name: <input type="text" name="cc_name" value="<?php echo $customer['cc_name']; ?>"></p>
        <p>Credit Card Issuing Bank: <input type="text" name="cc_bank" value="<?php echo $customer['cc_bank']; ?>"></p>
        <input type="submit" value="Update">
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
