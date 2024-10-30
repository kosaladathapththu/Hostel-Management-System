<?php
include 'db_connect.php';

$booking_id = $_GET['id'];

// Update the status to 'declined'
$query = "UPDATE bookings SET status = 'declined' WHERE booking_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $booking_id);

if ($stmt->execute()) {
    echo "Check-in request declined successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

// Redirect back to manage check-in/check-out page
header("Location: manage_checkin_checkout.php");
exit();
?>
