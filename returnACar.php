<?php
include "db.inc.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carReferenceNumber = $_POST['carReferenceNumber'];

    try {
        $pdo = db_connect();
        $query = "UPDATE rentals r
                  JOIN cars c ON r.car_id = c.id
                  SET r.status = 'returning', r.return_location = c.pickUp
                  WHERE c.carReferenceNumber = :carReferenceNumber";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':carReferenceNumber', $carReferenceNumber);
        $stmt->execute();

        header('Location: returnCar.php'); // Redirect back to the rented cars page
        exit;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
