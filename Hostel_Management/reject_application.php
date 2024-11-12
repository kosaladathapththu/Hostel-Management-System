<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

if (isset($_GET['id'])) {
    $application_id = $_GET['id'];

    // Update the application status to rejected
    $query = "UPDATE job_applications SET status = 'rejected' WHERE application_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $stmt->close();

    header("Location: view_applications.php?message=Application Rejected");
    exit();
} else {
    echo "Invalid application ID.";
}
?>
