<?php
include 'db_connect.php'; // Include database connection

// Check if the supplier ID is provided in the URL
if (isset($_GET['id'])) {
    $supplierId = $_GET['id'];

    // Prepare the delete query
    $deleteQuery = "DELETE FROM Suppliers WHERE supplier_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $supplierId);
    $stmt->execute();

    // Redirect back to the suppliers list after deletion
    if ($stmt->affected_rows > 0) {
        echo "<script>
                alert('Supplier deleted successfully!');
                window.location.href = 'view_suppliers.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: Unable to delete supplier.');
                window.location.href = 'view_suppliers.php';
              </script>";
    }

    $stmt->close(); // Close the statement
} else {
    echo "<script>
            alert('Invalid request. Supplier ID not provided.');
            window.location.href = 'view_suppliers.php';
          </script>";
}

$conn->close(); // Close the database connection
?>
