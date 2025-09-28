<?php
include "db.inc.php";
session_start();
if (!isset($_SESSION['username']) || $_SESSION['type'] !== 2) {
    // Redirect to login page or unauthorized access page
    header('Location: login.php'); // Replace with your login page URL
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carReferenceNumber = $_POST['carReferenceNumber'];

    try {
        $pdo = db_connect();
        $query = "SELECT c.carReferenceNumber, c.carModel, c.carType, c.carMake, r.pickup_date, r.return_date, r.pickup_location, r.return_location, u.username
                  FROM cars c
                  JOIN rentals r ON c.id = r.car_id
                  JOIN user_tablename u ON r.username = u.username
                  WHERE c.carReferenceNumber = :carReferenceNumber AND r.status = 'returning'";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':carReferenceNumber', $carReferenceNumber);
        $stmt->execute();
        $car = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as associative array
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complete Return</title>
    <link rel="stylesheet" href="Styles.css">
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
        <h1>Complete Return</h1>
        <form action="complete_return_process.php" method="POST">
            <input type="hidden" name="carReferenceNumber" value="<?php echo $car['carReferenceNumber']; ?>">
            <div>
                <label>Car Model:</label>
                <input type="text" value="<?php echo $car['carModel']; ?>" disabled>
            </div>
            <div>
                <label>Car Type:</label>
                <input type="text" value="<?php echo $car['carType']; ?>" disabled>
            </div>
            <div>
                <label>Car Make:</label>
                <input type="text" value="<?php echo $car['carMake']; ?>" disabled>
            </div>
            <div>
                <label>Pickup Date:</label>
                <input type="text" value="<?php echo $car['pickup_date']; ?>" disabled>
            </div>
            <div>
                <label>Return Date:</label>
                <input type="text" value="<?php echo $car['return_date']; ?>" disabled>
            </div>
            <div>
                <label>Pickup Location:</label>
                <input type="text" name="pickup_location" value="<?php echo isset($car['pickup_location']) ? $car['pickup_location'] : ''; ?>">
            </div>
            <div>
                <label>Return Location:</label>
                <input type="text" value="<?php echo $car['return_location']; ?>" disabled>
            </div>
            <div>
                <label>Status:</label>
                <select name="status">
                    <option value="available">Available</option>
                    <option value="damaged">Damaged</option>
                    <option value="repair">Repair</option>
                </select>
            </div>
            <button type="submit">Complete Return</button>
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
<?php } ?>
