<?php
session_start();
include 'db_connect.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to admin login if not logged in
    exit;
}

// Get the application ID from the URL
$application_id = $_GET['application_id'];

// Update the status to 'declined'
$query = "UPDATE leave_applications SET status = 'declined' WHERE application_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $application_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Leave request declined successfully.";
    header("Location: view_leave_requests.php"); // Redirect back to the leave requests page
} else {
    echo "Failed to decline the leave request.";
}

$stmt->close();
$conn->close();
?>
