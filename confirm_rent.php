<?php
session_start();
include("db.inc.php");

$pdo = db_connect();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Retrieve rental details from session
$rentDetails = $_SESSION['rent_details'];

// Generate a 10-digit invoice ID
$invoiceId = generateInvoiceId();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Store the rental confirmation details in the database
    $query = "INSERT INTO rentals (username, car_id, pickup_date, return_date, pickup_location, return_location, diff_location, baby_seat, insurance, total_cost, card_number, expiration_date, card_holder, bank_issued, card_type, accept_terms, signature, date, invoice_id, invoiceDate)
              VALUES (:username, :car_id, :pickup_date, :return_date, :pickup_location, :return_location, :diff_location, :baby_seat, :insurance, :total_cost, :card_number, :expiration_date, :card_holder, :bank_issued, :card_type, :accept_terms, :signature, :date, :invoice_id, CURRENT_DATE())";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':username', $_SESSION['username']);
    $stmt->bindValue(':car_id', $rentDetails['carId']); 
    $stmt->bindValue(':pickup_date', $rentDetails['pickup_date_time']);
    $stmt->bindValue(':return_date', $rentDetails['return_date_time']);
    $stmt->bindValue(':pickup_location', $rentDetails['pickup_location']);
    $stmt->bindValue(':return_location', $rentDetails['return_location']);
    // storing as boolean
    $stmt->bindValue(':diff_location', isset($_POST['diff_location']) ? 1 : 0); 
    $stmt->bindValue(':baby_seat', isset($_POST['baby_seat']) ? 1 : 0); 
    $stmt->bindValue(':insurance', isset($_POST['insurance']) ? 1 : 0); 
    $stmt->bindValue(':total_cost', $rentDetails['total_cost']);
    $stmt->bindValue(':card_number', $_POST['card_number']);
    $stmt->bindValue(':expiration_date', $_POST['expiration_date']);
    $stmt->bindValue(':card_holder', $_POST['card_holder']);
    $stmt->bindValue(':bank_issued', $_POST['bank_issued']);
    $stmt->bindValue(':card_type', $_POST['card_type']);
    $stmt->bindValue(':accept_terms', isset($_POST['accept_terms']) ? 1 : 0); 
    $stmt->bindValue(':signature', $_POST['signature']);
    $stmt->bindValue(':date', $_POST['date']);
    $stmt->bindValue(':invoice_id', $invoiceId);
    $stmt->execute();

    // Clear the session rental details
    unset($_SESSION['rent_details']);

    // Redirect to a confirmation page with invoice ID
    header("Location: confirmation.php?invoiceId=" . $invoiceId);
    exit;
}

function generateInvoiceId() {
    // Generate a random 10-digit number
    return str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Rent</title>
    <link rel="stylesheet" href="viewstyles.css">
</head>
<body>
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
    <h1>Confirm Rent</h1>
    <p>Your rental has been successfully confirmed!</p>
    <p>Thank you for choosing our service. Your car has been reserved.</p>
    <p>Invoice ID: <strong><?php echo $invoiceId; ?></strong></p>
    <p>An email with the rental details and invoice will be sent to you shortly.</p>
    <a href="logout.php">Logout</a>
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
