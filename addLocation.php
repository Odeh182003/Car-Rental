<?php
include "db.inc.php";
session_start();
try {
$pdo = db_connect();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = "Insert INTO locations  (location_name, property_number, street_name, city, postal_code, country, telephone_number) values (:location_name, :property_number, :street_name, :city, :postal_code, :country, :telephone_number)";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':location_name', $_POST['location_name']);
    $stmt->bindValue(':property_number', $_POST['property_number']);
    $stmt->bindValue(':street_name', $_POST['street_name']);
    $stmt->bindValue(':city', $_POST['city']);
    $stmt->bindValue(':postal_code', $_POST['postal_code']);
    $stmt->bindValue(':country', $_POST['country']);
    $stmt->bindValue(':telephone_number', $_POST['telephone_number']);
    //$stmt->execute();
    

    // Set parameters and execute
    $name = $_POST['location_name'];
    $address = $_POST['property_number']; 
    $street = $_POST['street_name'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];
    $telephone = $_POST['telephone_number'];

    if ($stmt->execute()) {
        // Retrieve last insert id which is the location ID
        $location_id = $pdo->lastInsertId();
    echo "New location added successfully. Location ID: " . $location_id;
    } else {
        echo "Error: " ;
    }
    
    
    
}
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
// Close  connection
$pdo = null;

?>
<html>
    <head>
        <title>
            Insert A new Location
        </title>
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
        <h1 style="padding: 25px;">Insert A new Location</h1>
        <div class="form-container">
        <form action="addLocation.php" method="post">
            <label for="location_name"> Location Name: </label>
            <input type="text" id="location_name" name="location_name" required>
            <label for="property_number">Property Number: </label>
            <input type="number" id="property_number" name="property_number" required>
            <label for="street_name">Street Name: </label>
            <input type="text" id="street_name" name="street_name" required>
            <label for="city">City: </label>
            <input type="text" id="city" name="city" required>
            <label for="postal_code">Postal Code: </label>
            <input type="text" id="postal_code" name="postal_code" required>
            <label for="country">Country: </label>
            <input type="text" id="country" name="country" required>
            <label for="telephone_number">Telephone Number: </label>
            <input type="tel" id="telephone_number" name="telephone_number" pattern="\d{3}-\d{3}-\d{4}" placeholder="Format:123-456-7890" required>
            <input type="submit" value="Add Location">
        </form>
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