<?php
include 'db_connect.php'; // Include database connection

if (isset($_GET['id'])) {
    $supplierId = $_GET['id'];

    if (!is_numeric($supplierId)) {
        die("<script>
                alert('Invalid supplier ID.');
                window.location.href = 'view_suppliers.php';
            </script>");
    }

    echo "Debug: Supplier ID to delete is " . $supplierId; // Debugging

    // Prepare the delete query
    $deleteQuery = "DELETE FROM Suppliers WHERE supplier_id = ?";
    $stmt = $conn->prepare($deleteQuery);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error); // Debugging
    }

    $stmt->bind_param("i", $supplierId);
    $stmt->execute();

    // Debug affected rows
    echo "Debug: Affected Rows: " . $stmt->affected_rows;

    if ($stmt->affected_rows > 0) {
        echo "<script>
                alert('Supplier deleted successfully!');
                window.location.href = 'view_suppliers.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: Unable to delete supplier. It may not exist.');
                window.location.href = 'view_suppliers.php';
              </script>";
    }

    $stmt->close();
} else {
    echo "<script>
            alert('Invalid request. Supplier ID not provided.');
            window.location.href = 'view_suppliers.php';
          </script>";
}

$conn->close();
?>
