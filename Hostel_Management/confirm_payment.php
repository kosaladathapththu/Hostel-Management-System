<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];

    // Update payment confirmation
    $confirmQuery = "UPDATE orderpayments SET payment_status = 'Confirmed' WHERE order_id = ?";
    $stmt = $conn->prepare($confirmQuery);
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        echo "Payment for Order ID $order_id has been confirmed.";
        header("Location: supplier_dashboard.php"); // Redirect back to the supplier dashboard
        exit();
    } else {
        echo "Failed to confirm payment. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>
