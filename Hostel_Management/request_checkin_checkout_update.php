<?php
include 'db_connect.php';
session_start();

$resident_id = $_SESSION['resident_id'];
$new_checkin = $_POST['new_checkin'];
$new_checkout = $_POST['new_checkout'];

// Insert or update check-in/check-out request in bookings
$updateRequestQuery = "INSERT INTO bookings (resident_id, check_in_date, check_out_date, status)
                       VALUES (?, ?, ?, 'pending_approval')
                       ON DUPLICATE KEY UPDATE 
                       check_in_date = VALUES(check_in_date), 
                       check_out_date = VALUES(check_out_date), 
                       status = 'pending_approval'";
$stmt = $conn->prepare($updateRequestQuery);
$stmt->bind_param("iss", $resident_id, $new_checkin, $new_checkout);

if ($stmt->execute()) {
    header("Location: resident_dashboard.php?msg=request_submitted");
} else {
    echo "Error: " . $stmt->error;
}
?>
