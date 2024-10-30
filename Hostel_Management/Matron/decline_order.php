<?php
include 'db_connect.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Update the order status to 'declined'
    $sql = "UPDATE orders SET status = 'declined' WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        echo "Order declined!";
    } else {
        echo "Error updating order: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: supplier_dashboard.php");
    exit();
}
?>
