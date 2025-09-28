<?php
session_start();
include("db.inc.php"); 

try {
    $pdo = db_connect(); 

    // Prepare base query
    $query = "SELECT c.id AS car_id, c.carType, c.carModel, c.briefDescription, c.carPhoto, c.fuelType, c.status 
              FROM cars c
              LEFT JOIN rentals r ON c.id = r.car_id
              WHERE 1=1"; // Always true, to make appending conditions easier

    // Prepare parameters array for binding
    $params = array();

    // Process form inputs to construct dynamic query
    if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
        // Filter by available for a certain period
        $query .= " AND (c.id NOT IN (
                        SELECT car_id FROM rentals 
                        WHERE (pickup_date BETWEEN :from_date AND :to_date) OR 
                              (return_date BETWEEN :from_date AND :to_date)
                    ))";
        $params[':from_date'] = $_GET['from_date'];
        $params[':to_date'] = $_GET['to_date'];
    }

    if (!empty($_GET['pickup_location'])) {
        // Filter by pick-up location
        $query .= " AND r.pickup_location = :pickup_location";
        $params[':pickup_location'] = $_GET['pickup_location'];
    }

    if (!empty($_GET['return_date'])) {
        // Filter by return on a certain day
        $query .= " AND r.return_date = :return_date";
        $params[':return_date'] = $_GET['return_date'];
    }

    if (!empty($_GET['return_location'])) {
        // Filter by return to a certain location
        $query .= " AND r.return_location = :return_location";
        $params[':return_location'] = $_GET['return_location'];
    }

    if (isset($_GET['include_repair'])) {
        // Filter by cars in repair
        $query .= " AND c.status = 'In Repair'";
    }

    if (isset($_GET['include_damage'])) {
        // Filter by cars in damage
        $query .= " AND c.status = 'Damaged'";
    }

    if (empty($_GET)) {
        // If no search options are selected, display all available cars for a week from the current date
        $query .= " AND (c.id NOT IN (
                        SELECT car_id FROM rentals 
                        WHERE (pickup_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)) OR 
                              (return_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY))
                    ))";
    }

    // Order by carType and carModel (example)
    $query .= " ORDER BY c.carType, c.carModel";

    // Prepare and execute the query
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cars Inquiry</title>
    <link rel="stylesheet" href="Styles.css"> 
    <style>
    /* Style the form container */
.form-row {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.form-row label {
    min-width: 180px;  /* same width for all labels */
    font-weight: bold;
    text-align: right;
    margin-right: 10px;
}

/* Inputs styling */
.form-row input[type="text"],
.form-row input[type="date"] {
    padding: 5px;
    width: 200px;
}

/* Checkboxes stay aligned with labels */
.form-row input[type="checkbox"] {
    width: auto;
    margin-left: 0;
}

/* Button row styling */
.form-row input,
.form-row button {
    flex: 1;
}

</style>
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
        <img src="img/carlogo.JPG" alt="Agency Logo" class="logo">
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
    <h1 style="padding: 25px;">Car Inquiry</h1>
    <form method="GET" action="carInquire.php">
    <div class="form-row">
        <label for="from_date">From Date:</label>
        <input type="date" id="from_date" name="from_date">
    </div>

    <div class="form-row">
        <label for="to_date">To Date:</label>
        <input type="date" id="to_date" name="to_date">
    </div>

    <div class="form-row">
        <label for="pickup_location">Pick-up Location:</label>
        <input type="text" id="pickup_location" name="pickup_location">
    </div>

    <div class="form-row">
        <label for="return_date">Return Date:</label>
        <input type="date" id="return_date" name="return_date">
    </div>

    <div class="form-row">
        <label for="return_location">Return Location:</label>
        <input type="text" id="return_location" name="return_location">
    </div>

    <div class="form-row">
        <label for="include_repair">Include Cars in Repair:</label>
        <input type="checkbox" id="include_repair" name="include_repair">
    </div>

    <div class="form-row">
        <label for="include_damage">Include Cars in Damage:</label>
        <input type="checkbox" id="include_damage" name="include_damage">
    </div>

    <div class="form-row">
        <button type="submit">Search</button>
    </div>
</form>

    <h2>Cars Inquiry Results</h2>
    <?php if (count($cars) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Car ID</th>
                    <th>Type</th>
                    <th>Model</th>
                    <th>Description</th>
                    <th>Photo</th>
                    <th>Fuel Type</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cars as $car): ?>
                    <tr>
                        <td><?php echo $car['car_id']; ?></td>
                        <td><?php echo $car['carType']; ?></td>
                        <td><?php echo $car['carModel']; ?></td>
                        <td><?php echo $car['briefDescription']; ?></td>
                        <td>
                            <?php
                            $photoPaths = explode(',', $car['carPhoto']);
                            foreach ($photoPaths as $index => $photoPath) {
                                if ($index < 3) { // Limit to the first 3 photos
                                    $trimmedPath = trim($photoPath);
                                    echo "<img src='carsImages/$trimmedPath' alt='Car Photo' style='width: 100px; height: auto; margin-right: 5px;'>";
                                }
                            }
                            ?>
                        </td>
                        <td><?php echo $car['fuelType']; ?></td>
                        <td><?php echo $car['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No cars found matching the criteria.</p>
    <?php endif; ?>
    <a href="carInquire.php">Back to Search</a>
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
