<?php
include 'db_connect.php'; // Include database connection

// Fetch room ID from the URL
$roomId = $_GET['id'];

// Delete room from the database
$deleteQuery = "DELETE FROM Rooms WHERE room_id = $roomId";
if ($conn->query($deleteQuery) === TRUE) {
    echo "Room deleted successfully.";
} else {
    echo "Error: " . $deleteQuery . "<br>" . $conn->error;
}

$conn->close(); // Close the database connection

// Redirect back to the rooms list
header("Location: rooml.php");
exit();
?>
