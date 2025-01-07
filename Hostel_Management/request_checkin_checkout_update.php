<?php
include 'db_connect.php';
session_start();

// Check if resident is logged in
if (!isset($_SESSION['resident_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch resident ID
$resident_id = $_SESSION['resident_id'];

// Get the new check-in and check-out dates from the form
$new_checkin = $_POST['new_checkin'];
$new_checkout = $_POST['new_checkout'];

// Insert the data into the resident_checking-checkouts table
$query = "INSERT INTO `resident_checking-checkouts` (resident_id, check_in_date, check_out_date) 
          VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iss", $resident_id, $new_checkin, $new_checkout);

if ($stmt->execute()) {
    echo "Request successfully submitted!";
} else {
    echo "Error submitting request: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
