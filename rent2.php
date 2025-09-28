<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Retrieve rental details from session
$rentDetails = $_SESSION['rent_details'];

// Fetch available return locations from the database
include("db.inc.php");
$pdo = db_connect();
$query = "SELECT location_name FROM locations";
$stmt = $pdo->prepare($query);
$stmt->execute();
$returnLocations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Process form submission and calculate total cost
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['rent_details'] = array_merge($_SESSION['rent_details'], $_POST);

    $pricePerDay = $rentDetails['totalPrice'];
    $pickupDate = new DateTime($_SESSION['rent_details']['pickup_date_time']);
    $returnDate = new DateTime($_SESSION['rent_details']['return_date_time']);
    $diff = $pickupDate->diff($returnDate);
    $days = $diff->days;
    $totalCost = $pricePerDay * $days;

    if (isset($_POST['diff_location'])) {
        $totalCost += 50; // Example cost for returning to a different location
    }

    if (isset($_POST['baby_seat'])) {
        $totalCost += 10; // Example cost for a baby seat
    }

    if (isset($_POST['insurance'])) {
        $totalCost += 20; // Example cost for insurance
    }

    $_SESSION['rent_details']['total_cost'] = $totalCost;

    header("Location: rent3.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rent a Car</title>
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
        <h1>Rent a Car</h1>
        <form action="rent2.php" method="POST">
            <span>
                <label>Car Reference Number:</label>
                <input type="text" name="car_reference_number" value="<?php echo $rentDetails['carReferenceNumber']; ?>" readonly>
            </span>
            <span>
                <label>Car Model:</label>
                <input type="text" name="car_model" value="<?php echo $rentDetails['carModel']; ?>" readonly>
            </span>
            <span>
                <label>Pickup Date and Time:</label>
                <input type="text" name="pickup_date_time" value="<?php echo $_SESSION['rent_details']['pickup_date_time']; ?>" readonly>
            </span>
            <span>
                <label>Return Date and Time:</label>
                <input type="text" name="return_date_time" value="<?php echo $_SESSION['rent_details']['return_date_time']; ?>" readonly>
            </span>
                <span>
                <label>Pickup Location:</label>
                <input type="text" name="pickup_location" value="<?php echo $rentDetails['pickUp']; ?>" readonly>
            </span>
            <span>
                <label>Total Rent Amount:</label>
                <input type="text" name="total_rent_amount" value="<?php echo $rentDetails['totalPrice']; ?>" readonly>
            </span>
            <span>
                <label>Return Location:</label>
                <select name="return_location" required>
                    <?php foreach ($returnLocations as $location): ?>
                        <option value="<?php echo $location['location_name']; ?>"><?php echo $location['location_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </span>
            <span>
                <label>Special Requirements:</label>
                <label><input type="checkbox" name="baby_seat" value="1"> Baby Seat</label>
                <label><input type="checkbox" name="insurance" value="1"> Insurance</label>
            </span>
            <button type="submit">Next</button>
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
