<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Details</title>
    <link rel="stylesheet" href="Styles.css">
</head>
<body>
    <?php
    session_start();
    ?>
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
            <img src="img/carlogo.JPG" alt="Agency Logo" class="logo">
            <h1 class="agency-name">Luxury Car Rental Agency</h1>
            <a href="AboutUs.html" class="about-us">About Us</a>
        </div>
        <div class="header-right">
            
            <?php
            if(isset($_SESSION['username'])){
             echo '<div class="user-info">';
             echo '<span class="user-name">User Name</span>';
             echo '<span class="username"><?php echo $_SESSION[\'username\']?></span>';
         echo '</div>';
            }
            ?>

           
            <div class="header-links">
                <a href="ModifyProfile.php" class="user-profile">Profile</a>
                <a href="#" class="shopping-basket">Shopping Basket</a>
                <a href="login.php" class="login-link">Login</a>
                <a href="logout.php" class="logout-link">Logout</a>
            </div>
        </div>
    </header>
    <?php
    
    include("db.inc.php");
    $pdo = db_connect();
    $carId = $_GET['carId'] ?? '';

    if ($carId) {
        $query = "SELECT * FROM cars WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$carId]);
        $car = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($car) {
            $rentingPeriod = new DateTime($car['rentingPeriod']);
            $endDate = (clone $rentingPeriod)->modify('+3 days');

            // Extract the first photo from the carPhoto field
            $carPhotos = explode(',', $car['carPhoto']);
            $firstCarPhoto = 'carsImages/' . $carPhotos[0];

            // HTML content to display car details
            echo "<div class='container'>";
            echo "<h1>Car Details</h1>";
            echo "<div class='car-details'>";
            
            // Car photos section
            echo "<div class='car-photos'>";
            echo "<img src='$firstCarPhoto' alt='Car Photo'>";
            echo "</div>";

            // Car description section
            echo "<div class='car-description'>";
            echo "<ul>";
            echo "<li>Car Reference Number: {$car['carReferenceNumber']}</li>";
            echo "<li>Car Model: {$car['carModel']}</li>";
            echo "<li>Car Type: {$car['carType']}</li>";
            echo "<li>Car Make: {$car['carMake']}</li>";
            echo "<li>Registration Year: {$car['registrationYear']}</li>";
            echo "<li>Color: {$car['color']}</li>";
            echo "<li>Brief Description: {$car['briefDescription']}</li>";
            echo "<li>Price Per Day: {$car['pricePerDay']}</li>";
            echo "<li>Capacity of People: {$car['capacityPeople']}</li>";
            echo "<li>Capacity of Suitcases: {$car['capacitySuitcases']}</li>";
            echo "<li>Total Price for the Renting Period: {$car['totalPrice']}</li>";
            echo "<li>Fuel Type: {$car['fuelType']}</li>";
            echo "<li>Average Consumption: {$car['averageConsumption']} L/100 km</li>";
            echo "<li>Horsepower: {$car['horsepower']}</li>";
            echo "<li>Length: {$car['length']} cm</li>";
            echo "<li>Width: {$car['width']} cm</li>";
            echo "<li>Gear Type: {$car['gearType']}</li>";
            echo "<li>Conditions/Restrictions: {$car['conditions']}</li>";
            echo "</ul>";

            // Rent button or login link based on user session
            if (isset($_SESSION['username'])) {
                echo "<div class='button-block'>";
                echo "<a class='rent-button' href='rent.php?carId={$carId}'>Rent This Car</a>";
                echo "</div>";
            } else {
                $_SESSION['redirect_to'] = "details.php?carId={$carId}";
                $redirectUrl = urlencode("details.php?carId={$carId}");
                echo "<div class='button-block'>";
                echo "<a class='rent-button' href='login.php?redirect=$redirectUrl'>Login to Rent This Car</a>";
                echo "</div>";
            }

            echo "</div>"; // Close .car-description

            // Left side advertising block
            echo "<div class='advertising-block'>";
            echo "<div class='marketing-info'>";
            echo "<p>This car is enjoyable to drive!</p>";
            echo "<p>Discounts available for long periods.</p>";
            echo "</div>";
            echo "</div>"; // Close .advertising-block

            echo "</div>"; // Close .car-details
            echo "</div>"; // Close .container
            
        } else {
            echo "Car not found.";
        }
    } else {
        echo "No car selected.";
    }
    ?>
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
