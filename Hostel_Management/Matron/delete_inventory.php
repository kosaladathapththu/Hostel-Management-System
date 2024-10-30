<?php
include 'db_connect.php'; // Include database connection

// Check if the item ID is provided in the URL
if (isset($_GET['id'])) {
    $itemId = intval($_GET['id']);

    // Delete the item from the database
    $deleteQuery = "DELETE FROM Inventory WHERE item_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $itemId);

    if ($stmt->execute()) {
        echo "<script>alert('Inventory item deleted successfully.'); window.location.href='view_inventory.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close(); // Close the prepared statement
} else {
    echo "Invalid request. Item ID not provided.";
}

$conn->close(); // Close the database connection
?>
