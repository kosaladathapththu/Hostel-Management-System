<?php
include 'db_connect.php';

// Fetch orders with bill details (JOIN with bill table using order_id)
$ordersQuery = "
SELECT 
    o.order_id, 
    o.item_name, 
    o.quantity, 
    o.order_date, 
    o.status AS order_status, 
    o.order_amount, 
    o.delivery_date, 
    o.approved_by, 
    o.remarks, 
    b.bill_amount, 
    b.bill_status
FROM Orders o
LEFT JOIN bill b ON o.order_id = b.order_id
";
$ordersResult = $conn->query($ordersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <th>Bill Amount</th> <!-- Bill Amount -->
                <th>Bill Status</th> <!-- Bill Status -->
                <th>Delivery Date</th>
                <th>Approved By</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $ordersResult->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                <td><?php echo htmlspecialchars($row['order_status']); ?></td>
                <td><?php echo htmlspecialchars($row['order_amount']); ?></td>
                <td><?php echo htmlspecialchars($row['bill_amount']); ?></td> <!-- Bill Amount -->
                <td><?php echo htmlspecialchars($row['bill_status']); ?></td> <!-- Bill Status -->
                <td><?php echo htmlspecialchars($row['delivery_date']); ?></td>
                <td><?php echo htmlspecialchars($row['approved_by']); ?></td>
                <td><?php echo htmlspecialchars($row['remarks']); ?></td>
                <td>
                    <a href="view_order.php?id=<?php echo $row['order_id']; ?>">View Order</a>
                    <a href="generate_receipt.php?id=<?php echo $row['order_id']; ?>">Generate Receipt</a>
                </td>
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
