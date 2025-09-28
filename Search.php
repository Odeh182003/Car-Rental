<?php
include("db.inc.php");

$pdo = db_connect();

$fromDate = $_GET['fromDate'] ?? '';
$toDate = $_GET['toDate'] ?? '';
$carType = $_GET['carType'] ?? '';
$pickup = $_GET['pickup'] ?? '';
$minPrice = $_GET['minPrice'] ?? '';
$maxPrice = $_GET['maxPrice'] ?? '';
$sortColumn = $_GET['sortColumn'] ?? 'pricePerDay';
$sortOrder = $_GET['sortOrder'] ?? 'ASC';

$currentDate = (new DateTime())->format('Y-m-d');
$defaultEndDate = (new DateTime($currentDate))->modify('+3 days')->format('Y-m-d');

// Default values if fields are empty
if ($fromDate === '') $fromDate = $currentDate;
if ($toDate === '') $toDate = $defaultEndDate;
if ($carType === '') $carType = 'sedan';
if ($pickup === '') $pickup = 'Birzeit';
if ($minPrice === '') $minPrice = 200;
if ($maxPrice === '') $maxPrice = 1000;

$query = "SELECT * FROM cars WHERE 1=1";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['filter'])) {
    // Apply filters
    $query .= " AND rentingPeriod >= :fromDate AND rentingPeriod <= :toDate";
    $query .= " AND carType = :carType";
    $query .= " AND pickUp = :pickup";
    $query .= " AND pricePerDay >= :minPrice";
    $query .= " AND pricePerDay <= :maxPrice";
}

$query .= " ORDER BY $sortColumn $sortOrder";
$stmt = $pdo->prepare($query);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['filter'])) {
    $stmt->bindValue(':fromDate', $fromDate);
    $stmt->bindValue(':toDate', $toDate);
    $stmt->bindValue(':carType', $carType);
    $stmt->bindValue(':pickup', $pickup);
    $stmt->bindValue(':minPrice', $minPrice, PDO::PARAM_INT);
    $stmt->bindValue(':maxPrice', $maxPrice, PDO::PARAM_INT);
}

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Search</title>
    <link rel="stylesheet" href="Styles.css">
</head>
<body>
<h1>Available Rental Car Search</h1>
<form action="Search.php" method="get">
    <section>
        <label for="fromDate">From Date:</label>
        <input type="date" id="fromDate" name="fromDate" value="<?php echo $fromDate; ?>">
        <label for="toDate">To Date:</label>
        <input type="date" id="toDate" name="toDate" value="<?php echo $toDate; ?>">
        <label for="carModel">Car Model:</label>
        <select id="carModel" name="carModel"><br>
            <option value="">Select car Model</option>
            <?php
            $query = "SELECT DISTINCT carModel FROM cars";
            $stmt = $pdo->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . $row['carModel'] . "'>" . $row['carModel'] . "</option>";
            }
            ?>
        </select>
        <label for="pickup">Pick up location:</label>
        <input type="text" id="pickup" name="pickup" placeholder="BIRZEIT" value="<?php echo $pickup; ?>">
        <label for="minPrice">Min Price:</label>
        <input type="number" id="minPrice" name="minPrice" value="<?php echo $minPrice; ?>">
        <label for="maxPrice">Max Price:</label>
        <input type="number" id="maxPrice" name="maxPrice" value="<?php echo $maxPrice; ?>">
        <div style="width: 100%; text-align: center;">
            <input type="submit" name="filter" value="Filter">
        </div>
    </section>
</form>

<table>
    <caption>Our Cars for Rental</caption>
    <thead>
        <tr>
            <th><button type="button" onclick="filterCheckedItems()">checked</button></th>
            <th><a href="Search.php?sortColumn=pricePerDay&sortOrder=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Price Per Day</a></th>
            <th><a href="Search.php?sortColumn=carType&sortOrder=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Car Type</a></th>
            <th>Fuel Type</th>
            <th>Car Photo</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($result as $row) {
        $carPhotos = explode(',', $row['carPhoto']);
        $firstCarPhoto = 'carsImages/' . $carPhotos[0];  // Path to the first car photo
        echo "<tr class='{$row['fuelType']}'>"; // Add class based on fuelType
        echo "<td><input type='checkbox' class='carCheckbox'></td>";
        echo "<td>{$row['pricePerDay']}</td>";
        echo "<td>{$row['carType']}</td>";
        echo "<td>{$row['fuelType']}</td>";
        echo "<td><img src='$firstCarPhoto' alt='Car Photo' width='100'></td>";
        echo "<td>
                <form action='details.php' method='GET'>
                    <input type='hidden' name='carId' value='{$row['id']}'>
                    <button type='submit' class='btn'>Rent</button>
                </form>
              </td>";
        echo "</tr>";
    }
    ?>
    </tbody>
</table>

<script>
function filterCheckedItems() {
    const checkboxes = document.querySelectorAll('.carCheckbox');
    checkboxes.forEach(checkbox => {
        const row = checkbox.closest('tr');
        if (!checkbox.checked) {
            row.style.display = 'none';
        }
    });
}
</script>
</body>
</html>
