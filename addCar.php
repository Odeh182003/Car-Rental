<?php
session_start();
include("db.inc.php");

// Check if the user is logged in and is a manager
if (!isset($_SESSION['username']) || $_SESSION['type'] !== 2) {
    header("Location: login.php");
    exit;
}

$pdo = db_connect();

// Fetch unique car makes, car types, and fuel types from the database
$carMakes = $pdo->query("SELECT DISTINCT carMake FROM cars")->fetchAll(PDO::FETCH_COLUMN);
$carTypes = $pdo->query("SELECT DISTINCT carType FROM cars")->fetchAll(PDO::FETCH_COLUMN);
$fuelTypes = $pdo->query("SELECT DISTINCT fuelType FROM cars")->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carReferenceNumber = str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
    $carModel = $_POST['car_model'];
    $carMake = $_POST['car_make'];
    $carType = $_POST['car_type'];
    $registrationYear = $_POST['registration_year'];
    $briefDescription = $_POST['brief_description'];
    $pricePerDay = $_POST['price_per_day'];
    $capacityPeople = $_POST['capacity_people'];
    $capacitySuitcases = $_POST['capacity_suitcases'];
    $color = $_POST['color'];
    $fuelType = $_POST['fuel_type'];
    $averageConsumption = $_POST['average_consumption'];
    $horsepower = $_POST['horsepower'];
    $length = $_POST['length'];
    $width = $_POST['width'];
    $gearType = $_POST['gear_type'];
    $conditions = $_POST['conditions'];
    $pickUp = $_POST['pick_up'];
    $totalPrice = $pricePerDay; // Initialize with base price per day
    $rentingPeriod = $_POST['renting_period'] ?? ''; // Ensure renting period is handled correctly

    // Adjust total price based on additional options
    if (isset($_POST['insurance']) && $_POST['insurance'] == 1) {
        $totalPrice += 50; // Example: $50 for insurance
    }
    if (isset($_POST['baby_seat']) && $_POST['baby_seat'] == 1) {
        $totalPrice += 20; // Example: $20 for baby seat
    }

    // Check if at least three photos are uploaded
    if (count($_FILES['photos']['name']) < 3) {
        echo "Please upload at least three photos.";
        exit;
    }

    // Validate and upload photos
    $photoPaths = [];
    $uploadDir = 'carsImages/';
    foreach ($_FILES['photos']['name'] as $index => $name) {
        $fileType = pathinfo($name, PATHINFO_EXTENSION);
        if (!in_array($fileType, ['jpeg', 'png', 'jpg'])) {
            echo "Invalid file type. Only jpeg, png, and jpg are allowed.";
            exit;
        }

        $newFileName = $carReferenceNumber . 'img' . ($index + 1) . '.' . $fileType;
        $targetFilePath = $uploadDir . $newFileName;

        if (!move_uploaded_file($_FILES['photos']['tmp_name'][$index], $targetFilePath)) {
            echo "Error uploading file.";
            exit;
        }

        $photoPaths[] = $newFileName; // Store only the filename
    }

    try {
        // Insert car details into the database
        $stmt = $pdo->prepare("
            INSERT INTO cars (
                carReferenceNumber, carModel, carType, carMake, registrationYear, briefDescription, 
                pricePerDay, capacityPeople, capacitySuitcases, color, fuelType, averageConsumption, 
                horsepower, length, width, gearType, conditions, carPhoto, totalPrice, pickUp, rentingPeriod
            ) VALUES (
                :carReferenceNumber, :carModel, :carType, :carMake, :registrationYear, :briefDescription, 
                :pricePerDay, :capacityPeople, :capacitySuitcases, :color, :fuelType, :averageConsumption, 
                :horsepower, :length, :width, :gearType, :conditions, :carPhoto, :totalPrice, :pickUp, :rentingPeriod
            )
        ");
        
        $stmt->execute([
            ':carReferenceNumber' => $carReferenceNumber,
            ':carModel' => $carModel,
            ':carType' => $carType,
            ':carMake' => $carMake,
            ':registrationYear' => $registrationYear,
            ':briefDescription' => $briefDescription,
            ':pricePerDay' => $pricePerDay,
            ':capacityPeople' => $capacityPeople,
            ':capacitySuitcases' => $capacitySuitcases,
            ':color' => $color,
            ':fuelType' => $fuelType,
            ':averageConsumption' => $averageConsumption,
            ':horsepower' => $horsepower,
            ':length' => $length,
            ':width' => $width,
            ':gearType' => $gearType,
            ':conditions' => $conditions,
            ':carPhoto' => implode(',', $photoPaths), // Store photo filenames separated by commas
            ':totalPrice' => $totalPrice,
            ':pickUp' => $pickUp,
            ':rentingPeriod' => $rentingPeriod
        ]);

        echo "Car added successfully. Car ID: " . $pdo->lastInsertId();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add A Car</title>
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
        <h1 style="padding: 25px;">Add A Car</h1>
        <form action="addCar.php" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <label>Car Model:</label>
                <input type="text" name="car_model" required>
            </div>
            <div class="form-row">
                <label>Car Make:</label>
                <select name="car_make" required>
                    <?php foreach ($carMakes as $make): ?>
                        <option value="<?php echo $make; ?>"><?php echo $make; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row">
                <label>Car Type:</label>
                <select name="car_type" required>
                    <?php foreach ($carTypes as $type): ?>
                        <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row">
                <label>Registration Year:</label>
                <input type="Year" name="registration_year" required>
            </div>
            <div class="form-row">
                <label>Brief Description:</label>
                <textarea name="brief_description" required></textarea>
            </div>
            <div class="form-row">
                <label>Price Per Day:</label>
                <input type="number" step="5" name="price_per_day" max=100 required>
            </div>
            <div class="form-row">
                <label>Capacity (People):</label>
                <input type="number" name="capacity_people"  max=5 required>
            </div>
            <div class="form-row">
                <label>Capacity (Suitcases):</label>
                <input type="number" name="capacity_suitcases" max=3 required>
            </div>
            <div class="form-row">
                <label>Color:</label>
                <input type="text" name="color" required>
            </div>
            <div class="form-row">
                <label>Fuel Type:</label>
                <select name="fuel_type" required>
                    <?php foreach ($fuelTypes as $fuel): ?>
                        <option value="<?php echo $fuel; ?>"><?php echo $fuel; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row">
                <label>Average Petroleum Consumption (per 100 km):</label>
                <input type="number" step="15" name="average_consumption" max=1000 required>
            </div>
            <div class="form-row">
                <label>Horsepower:</label>
                <input type="number" name="horsepower" required>
            </div>
            <div class="form-row">
                <label>Length:</label>
                <input type="number" name="length" required>
            </div>
            <div class="form-row">
                <label>Width:</label>
                <input type="number" name="width" required>
            </div>
            <div class="form-row">
                <label>Gear Type:</label>
                <input type="text" name="gear_type" required>
            </div>
            <div class="form-row">
                <label>Conditions:</label>
                <input type="text" name="conditions" required>
            </div>

            <div class="form-row">
                <label>Pick Up Location:</label>
                <input type="text" name="pick_up" required>
            </div>
            <div class="form-row">
                <label>Plate Number:</label>
                <input type="text" name="plate_number" required>
            </div>
            <div class="form-row">
                <label>Insurance:</label>
                <input type="checkbox" name="insurance" value="1"> Yes
            </div>
            <div class="form-row">
                <label>Baby Seat:</label>
                <input type="checkbox" name="baby_seat" value="1"> Yes
            </div>
            <div class="form-row">
            <label>Renting Period:</label>
            <input type="date" name="renting_period" required>
            </div>
            <div class="form-row">
                <label>Photos:</label>
                <input type="file" name="photos[]" multiple required>
            </div>
            <div class="form-row">
                <button type="submit">Add Car</button>
            </div>
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
