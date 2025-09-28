<?php
include "db.inc.php";
session_start();

if (!isset($_SESSION['username']) || $_SESSION['type'] !== 2) {
    // Redirect to login page or unauthorized access page
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carReferenceNumber = $_POST['carReferenceNumber'];
    $pickup_location = $_POST['pickup_location'];
    $status = $_POST['status'];

    try {
        $pdo = db_connect();
        
        // Begin a transaction to ensure both updates are atomic
        $pdo->beginTransaction();
        
        // Update rentals table
        $queryRentals = "UPDATE rentals r
                         JOIN cars c ON r.car_id = c.id
                         SET r.pickup_location = :pickup_location, r.status = :status
                         WHERE c.carReferenceNumber = :carReferenceNumber AND r.status = 'returning'";
        $stmtRentals = $pdo->prepare($queryRentals);
        $stmtRentals->bindValue(':carReferenceNumber', $carReferenceNumber);
        $stmtRentals->bindValue(':pickup_location', $pickup_location);
        $stmtRentals->bindValue(':status', $status);
        $stmtRentals->execute();

        // Update cars table
        $queryCars = "UPDATE cars
                      SET status = :status
                      WHERE carReferenceNumber = :carReferenceNumber";
        $stmtCars = $pdo->prepare($queryCars);
        $stmtCars->bindValue(':carReferenceNumber', $carReferenceNumber);
        $stmtCars->bindValue(':status', $status);
        $stmtCars->execute();

        // Commit the transaction
        $pdo->commit();

        header('Location: manager_returns.php'); // Redirect back to the manager's returns page
        exit;
    } catch (PDOException $e) {
        // Rollback the transaction on error
        $pdo->rollBack();
        echo 'Error: ' . $e->getMessage();
    }
}
?>
