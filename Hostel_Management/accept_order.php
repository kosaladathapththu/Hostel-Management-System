<?php
include 'db_connect.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Update the order status to 'Accepted' and redirect to bill creation page
    $sql = "UPDATE Orders 
            SET status = 'Accepted' 
            WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to the page where the bill can be assigned
    header("Location: create_bill.php?order_id=" . $order_id);
    exit();
}

$conn->close();
?>