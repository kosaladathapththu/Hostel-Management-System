<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $application_id = $_GET['id'];

    // Update the leave request status to declined
    $query = "UPDATE leave_applications SET status = 'declined' WHERE application_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $application_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Leave request declined successfully.";
    } else {
        echo "Error declining leave request.";
    }
    
    $stmt->close();
}

$conn->close();
header("Location: view_leave_requests.php");
exit();
?>
