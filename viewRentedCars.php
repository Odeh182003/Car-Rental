<?php
// view_rented_cars.php

include "db.inc.php"; // Include your database connection script
session_start();
try {
    $pdo = db_connect(); // Connect to the database

    // Query to fetch rental information
    $query = "SELECT r.invoice_id, r.invoiceDate, c.carType, c.carModel, r.pickup_date, r.pickup_location, r.return_date, r.return_location
              FROM rentals r
              JOIN cars c ON r.car_id = c.id
              WHERE r.username = :username
              ORDER BY r.pickup_date"; // Order by pickup_date ascending
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $_SESSION['username']); // Assuming customer username is stored in session
    $stmt->execute();
    $rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Rented Cars</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css">
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
        <h1>View Rented Cars</h1>
        <table>
            <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th>Invoice Date</th>
                    <th>Car Type</th>
                    <th>Car Model</th>
                    <th>Pick-up Date</th>
                    <th>Pick-up Location</th>
                    <th>Return Date</th>
                    <th>Return Location</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rentals as $rental): ?>
                    <?php
                        // Determine rental status based on pickup_date and return_date
                        $now = date('Y-m-d');
                        $pickupDate = $rental['pickup_date'];
                        $returnDate = $rental['return_date'];
                        if ($pickupDate > $now) {
                            $status = 'future';
                        } elseif ($pickupDate <= $now && $returnDate >= $now) {
                            $status = 'current';
                        } else {
                            $status = 'past';
                        }
                    ?>
                    <tr class="<?php echo $status; ?>">
                        <td><?php echo $rental['invoice_id']; ?></td>
                        <td><?php echo $rental['invoiceDate']; ?></td>
                        <td><?php echo $rental['carType']; ?></td>
                        <td><?php echo $rental['carModel']; ?></td>
                        <td><?php echo $rental['pickup_date']; ?></td>
                        <td><?php echo $rental['pickup_location']; ?></td>
                        <td><?php echo $rental['return_date']; ?></td>
                        <td><?php echo $rental['return_location']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
