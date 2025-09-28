<?php
include "db.inc.php";
session_start();
if (!isset($_SESSION['username']) || $_SESSION['type'] !== 2) {
    // Redirect to login page or unauthorized access page
    header('Location: login.php'); // Replace with your login page URL
    exit;
}
try {
    $pdo = db_connect();
    $query = "SELECT c.carReferenceNumber, c.carModel, c.carType, c.carMake, r.pickup_date, r.return_date, r.pickup_location, r.return_location, u.username
              FROM cars c
              JOIN rentals r ON c.id = r.car_id
              JOIN user_tablename u ON r.username = u.username
              WHERE r.status = 'returning'";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Returning Cars</title>
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
                    <span class="username">(@username)</span>
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
        <h1>Returning Cars</h1>
        <table>
            <thead>
                <tr>
                    <th>Car Reference Number</th>
                    <th>Car Model</th>
                    <th>Car Type</th>
                    <th>Car Make</th>
                    <th>Pickup Date</th>
                    <th>Return Date</th>
                    <th>Pickup Location</th>
                    <th>Return Location</th>
                    <th>Customer Username</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?php echo $row['carReferenceNumber']; ?></td>
                        <td><?php echo $row['carModel']; ?></td>
                        <td><?php echo $row['carType']; ?></td>
                        <td><?php echo $row['carMake']; ?></td>
                        <td><?php echo $row['pickup_date']; ?></td>
                        <td><?php echo $row['return_date']; ?></td>
                        <td><?php echo $row['pickup_location']; ?></td>
                        <td><?php echo $row['return_location']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td>
                            <form action="complete_return.php" method="POST">
                                <input type="hidden" name="carReferenceNumber" value="<?php echo $row['carReferenceNumber']; ?>">
                                <button type="submit">Process Return</button>
                            </form>
                        </td>
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
