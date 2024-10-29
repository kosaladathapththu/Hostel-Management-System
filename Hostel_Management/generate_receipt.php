<?php
include 'db_connect.php'; // Include database connection

// Check if the order ID is set in the URL
if (isset($_GET['id'])) {
    $orderId = intval($_GET['id']); // Use intval to ensure it's an integer

    // Fetch the order details using a prepared statement
    $orderQuery = "
    SELECT o.order_id, s.supplier_name, o.item_name, o.quantity, o.order_date, o.status
    FROM Orders o
    JOIN Suppliers s ON o.supplier_id = s.supplier_id
    WHERE o.order_id = ?";
    
    $stmt = $conn->prepare($orderQuery); // Prepare the SQL statement
    $stmt->bind_param('i', $orderId); // Bind the order ID as an integer
    $stmt->execute(); // Execute the query
    $orderResult = $stmt->get_result(); // Get the result set
    $order = $orderResult->fetch_assoc(); // Fetch the order details

    // Check if order was found
    if (!$order) {
        echo "Order not found.";
        exit; // Stop further execution if order doesn't exist
    }
} else {
    echo "Invalid request. Order ID not provided.";
    exit; // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Receipt</title>
    <link rel="stylesheet" href="generate_receipt.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>Order Receipt</h1>
            <p>Salvation Army Girls Hostel</p>
            <p>Order ID: <?php echo htmlspecialchars($order['order_id']); ?></p>
            <p>Order Date: <?php echo htmlspecialchars($order['order_date']); ?></p>
        </div>
        
        <table class="receipt-details">
            <tr>
                <th>Supplier</th>
                <td><?php echo htmlspecialchars($order['supplier_name']); ?></td>
            </tr>
            <tr>
                <th>Item Name</th>
                <td><?php echo htmlspecialchars($order['item_name']); ?></td>
            </tr>
            <tr>
                <th>Quantity</th>
                <td><?php echo htmlspecialchars($order['quantity']); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo htmlspecialchars($order['status']); ?></td>
            </tr>
        </table>

        <div class="receipt-footer">
            <p>Thank you for placing your order.</p>
            <button class="print-button" onclick="window.print()">Print Receipt</button>
        </div>
    </div>

    <a href="view_order.php">Back to Orders</a>
</body>
</html>

<?php
$stmt->close(); // Close the prepared statement
$conn->close(); // Close the database connection
?>
