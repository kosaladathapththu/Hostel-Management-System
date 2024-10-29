<?php
include 'db_connect.php'; // Include database connection

// Fetch all orders
$ordersQuery = "SELECT o.order_id, o.item_name, o.quantity, o.order_date, o.status FROM Orders o";
$ordersResult = $conn->query($ordersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Orders</title>
    <link rel="stylesheet" href="view_order.css"> <!-- Link to your CSS file -->
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
        <th>Action</th>
    </tr>
    <?php while ($row = $ordersResult->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
            <td><?php echo htmlspecialchars($row['order_date']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td>
                <a href="view_order.php?id=<?php echo $row['order_id']; ?>">View Order</a>
                <a href="generate_receipt.php?id=<?php echo $row['order_id']; ?>">Generate Receipt</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

        <div>
            <a href="request_order.php">Request New Order</a>
            <a href="dashboard.php" class="dashboard-button">Dashboard</a>  
        </div>
    </section>
</body>
</html>


<?php
$conn->close();  
?>
