<?php
include 'db_connect.php';

// Check if the order_id is received from the form
if (isset($_POST['order_id'])) {
    $orderId = $_POST['order_id'];

    // SQL query to update the status column to 'Paid'
    $updateQuery = "UPDATE Orders SET status = 'Paid' WHERE order_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $orderId);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        echo "<script>
                alert('Payment confirmed successfully!');
                window.location.href = 'view_order.php'; // Redirect back to the orders page
              </script>";
    } else {
        echo "<script>
                alert('Error: Unable to update payment status.');
                window.location.href = 'view_order.php';
              </script>";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "<script>
            alert('No order ID received. Please try again.');
            window.location.href = 'view_order.php';
          </script>";
}

// Close the database connection
$conn->close();
?>
