<?php
include 'db_connect.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch payment details
    $paymentQuery = "SELECT * FROM orderpayments WHERE order_id = ?";
    $stmt = $conn->prepare($paymentQuery);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $payment = $result->fetch_assoc();
        echo "<h2>Payment Details for Order ID $order_id</h2>";
        echo "<p>Payment Status: " . htmlspecialchars($payment['payment_status']) . "</p>";
    } else {
        echo "No payment details found for this order.";
    }

    $stmt->close();
} else {
    echo "Order ID not provided.";
}
$conn->close();
?>
