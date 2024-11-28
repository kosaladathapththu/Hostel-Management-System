<?php
include 'db_connect.php'; // Include database connection

// Check if 'id' is present in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $residentId = intval($_GET['id']); // Get the resident ID from the URL

    // Prepare DELETE query to remove the resident
    $deleteQuery = "DELETE FROM residents WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);

    if ($stmt === false) {
        die('Error in preparing delete statement: ' . $conn->error);
    }

    $stmt->bind_param("i", $residentId); // Bind the ID parameter

    // Execute the DELETE query
    if ($stmt->execute()) {
        // Success - Redirect to the residents list
        header("Location: residents.php?success=1");
        exit();
    } else {
        // Failure - Show an error message
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    // Redirect back to residents list if 'id' is not found
    header("Location: residents.php");
    exit();
}

// Close the database connection
$conn->close(); 
?>
