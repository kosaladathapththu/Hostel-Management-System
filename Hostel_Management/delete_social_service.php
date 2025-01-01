<?php
session_start();
include 'db_connect.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Check if a service ID is provided to delete
if (isset($_GET['id'])) {
    $service_id = $_GET['id'];

    // Prepare and execute the delete query
    $deleteQuery = "DELETE FROM social_service WHERE service_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $service_id);

    if ($stmt->execute()) {
        // Redirect back to the social service list after successful deletion
        header("Location: view_social_service.php");
        exit;
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No service ID provided.";
}

$conn->close();
?>
