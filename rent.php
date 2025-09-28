<?php
session_start();
include("db.inc.php");

$pdo = db_connect();

// Check if 'carId' is set in the GET parameters
if (!isset($_GET['carId'])) {
    echo "No car ID specified.";
    exit;
}

// Fetch car details from the database
$carId = $_GET['carId'];
$query = "SELECT id AS carId, carReferenceNumber, carModel, totalPrice, pickUp FROM cars WHERE id = :carId"; // Aliasing 'id' as 'carId'
$stmt = $pdo->prepare($query);
$stmt->bindValue(':carId', $carId);
$stmt->execute();
$rentDetails = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$rentDetails) {
    echo "Car details not found.";
    exit;
}

// Store all retrieved rental details in the session
$_SESSION['rent_details'] = [
    'carId' => $rentDetails['carId'],  // Ensure 'carId' is set correctly
    'carReferenceNumber' => $rentDetails['carReferenceNumber'],
    'carModel' => $rentDetails['carModel'],
    'totalPrice' => $rentDetails['totalPrice'],
    'pickUp' => $rentDetails['pickUp'],
    // Add other details as needed
];

// Fetch available return locations from the database
$query = "SELECT location_name FROM locations";
$stmt = $pdo->prepare($query);
$stmt->execute();
$returnLocations = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['redirect_to'] = "rent.php?carId={$carId}";
    header("Location: login.php");
    exit;
}

// Process form submission and store data in session
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['rent_details']['pickup_date_time'] = $_POST['pickup_date_time'];
    $_SESSION['rent_details']['return_date_time'] = $_POST['return_date_time'];
    $_SESSION['rent_details']['return_location'] = $_POST['return_location'];
    $_SESSION['rent_details']['baby_seat'] = isset($_POST['baby_seat']) ? 1 : 0;
    $_SESSION['rent_details']['insurance'] = isset($_POST['insurance']) ? 1 : 0;

    header("Location: rent2.php?carId=" . $_GET['carId']);
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
        <form action="rent.php?carId=<?php echo $carId; ?>" method="POST">
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
                <input type="datetime-local" name="pickup_date_time" required>
            </span>
            <span>
                <label>Return Date and Time:</label>
                <input type="datetime-local" name="return_date_time" required>
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
