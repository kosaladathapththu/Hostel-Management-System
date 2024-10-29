<?php
include 'db_connect.php'; // Include database connection

// Check if event ID is provided
if (isset($_GET['id'])) {
    $eventId = intval($_GET['id']);

    // Delete the event
    $deleteQuery = "DELETE FROM Events WHERE event_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $eventId);

    if ($stmt->execute()) {
        echo "<script>alert('Event deleted successfully.'); window.location.href='view_calendar.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close(); // Close the prepared statement
} else {
    echo "Invalid request. Event ID not provided.";
}

$conn->close();
?>
