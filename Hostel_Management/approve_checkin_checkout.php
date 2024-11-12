<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    // Update booking status to 'approved'
    $updateQuery = "UPDATE bookings SET status = 'approved' WHERE booking_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $booking_id);
    
    if ($stmt->execute()) {
        echo "Booking approved successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}
header("Location: manage_checkin_checkout.php");
exit();
?>
