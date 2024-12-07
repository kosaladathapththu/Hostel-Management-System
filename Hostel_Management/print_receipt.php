<?php
include 'db_connect.php';

// Fetch the order details
if (isset($_GET['order_id'])) {
    $orderId = intval($_GET['order_id']); // Use intval to ensure it's an integer

    // Fetch the order details from the database
    $orderQuery = "SELECT o.order_id, s.supplier_name, o.item_name, o.quantity, o.order_date, o.status, o.bill_amount, o.delivery_date
                   FROM Orders o
                   JOIN Suppliers s ON o.supplier_id = s.supplier_id
                   WHERE o.order_id = ?";
    $stmt = $conn->prepare($orderQuery);
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $orderResult = $stmt->get_result();
    $order = $orderResult->fetch_assoc();

    if (!$order) {
        echo "Order not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Receipt</title>
    <link rel="stylesheet" href="receipt.css"> <!-- Link to a custom receipt style -->
</head>
<body>
    <div class="receipt-container">
        <h1>Order Receipt</h1>
        <p>Order ID: <?php echo htmlspecialchars($order['order_id']); ?></p>
        <p>Supplier: <?php echo htmlspecialchars($order['supplier_name']); ?></p>
        <p>Item: <?php echo htmlspecialchars($order['item_name']); ?></p>
        <p>Quantity: <?php echo htmlspecialchars($order['quantity']); ?></p>
        <p>Order Date: <?php echo htmlspecialchars($order['order_date']); ?></p>
        <p>Status: <?php echo htmlspecialchars($order['status']); ?></p>
        <p>Amount: <?php echo htmlspecialchars($order['bill_amount']); ?></p>
        <p>Delivery Date: <?php echo htmlspecialchars($order['delivery_date']); ?></p>

        <?php if (strtolower($order['status']) === 'paid') : ?>
            <div class="paid-seal">PAID</div>
        <?php endif; ?>

        <button onclick="window.print()">Print Receipt</button>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
