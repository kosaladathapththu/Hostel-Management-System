<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $applicant_id = $_GET['id'];

    // Step 1: Decline the resident by deleting from the applicants table
    $deleteQuery = "DELETE FROM applicants WHERE applicant_id = ?";
    $stmtDelete = $conn->prepare($deleteQuery);
    $stmtDelete->bind_param("i", $applicant_id);

    if ($stmtDelete->execute()) {
        header("Location: waiting_list.php?success=declined");
        exit();
    } else {
        echo "Error: " . $stmtDelete->error;
    }
} else {
    echo "Invalid request.";
}
?>
