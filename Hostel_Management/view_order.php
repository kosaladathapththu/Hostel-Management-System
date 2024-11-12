<?php
include 'db_connect.php';

// Fetch orders with bill details
$ordersQuery = "
SELECT o.order_id, o.item_name, o.quantity, o.order_date, o.status, 
       od.order_amount, od.delivery_date, s.supplier_acceptance, p.payment_status, b.bill_amount, b.bill_date, b.balance, b.bill_status
FROM Orders o
LEFT JOIN OrderDetails od ON o.order_id = od.order_id
LEFT JOIN OrderStatus s ON o.order_id = s.order_id
LEFT JOIN OrderPayments p ON o.order_id = p.order_id
LEFT JOIN Bill b ON o.order_id = b.order_id
";
$ordersResult = $conn->query($ordersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Orders</title>
    <link rel="stylesheet" href="view_order.css">
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - Orders List</h1>
        <p>User: [Matron Name] <a href="logout.php">Logout</a></p>
    </header>

    <section>
        <h2>Orders</h2>

        <table>
            <tr>
                <th>Order ID</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Order Amount</th>
                <th>Delivery Date</th>
                <th>Supplier Acceptance</th>
                <th>Payment Status</th>
                <th>Bill Amount</th>
                <th>Bill Date</th>
                <th>Balance</th>
                <th>Bill Status</th>
            </tr>
            <?php while ($row = $ordersResult->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td><?php echo htmlspecialchars($row['order_amount']); ?></td>
                <td><?php echo htmlspecialchars($row['delivery_date']); ?></td>
                <td><?php echo htmlspecialchars($row['supplier_acceptance']); ?></td>
                <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                <td><?php echo htmlspecialchars($row['bill_amount']); ?></td>
                <td><?php echo htmlspecialchars($row['bill_date']); ?></td>
                <td><?php echo htmlspecialchars($row['balance']); ?></td>
                <td><?php echo htmlspecialchars($row['bill_status']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <div>
        <a href="request_order.php">New Order</a>    
        <a href="dashboard.php">Dashboard</a>
        </div>
    </section>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
