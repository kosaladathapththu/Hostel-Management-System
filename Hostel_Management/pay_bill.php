<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];

    // Check if the order exists in the database
    $orderQuery = "SELECT * FROM orderpayments WHERE order_id = ?";
    $stmt = $conn->prepare($orderQuery);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();

        // Check if already paid
        if (strcasecmp($order['payment_status'], 'Paid') === 0) {
            echo "This order has already been paid!";
        } else {
            // Update the payment status to Paid
            $updateQuery = "UPDATE orderpayments SET payment_status = 'Paid' WHERE order_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("i", $order_id);

            if ($updateStmt->execute()) {
                echo "Payment for Order ID $order_id has been successfully updated to 'Paid'.";
                header("Location: view_orders.php"); // Redirect back to the orders page
                exit();
            } else {
                echo "Failed to update payment status. Please try again.";
            }
        }
    } else {
        echo "Invalid Order ID.";
    }

    $stmt->close();
    $conn->close();
}
?>
