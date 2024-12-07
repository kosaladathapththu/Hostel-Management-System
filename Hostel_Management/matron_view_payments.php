<?php
include 'db_connect.php';

session_start();

// Fetch all pending bills
$query = "SELECT o.order_id, o.item_name, o.bill_amount, p.payment_status 
          FROM orders o
          LEFT JOIN orderpayments p ON o.order_id = p.order_id
          WHERE p.payment_status = 'Pending'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Matron - Pending Payments</title>
    <link rel="stylesheet" href="matron_styles.css">
</head>
<body>
    <h1>Pending Bill Payments</h1>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Item Name</th>
                <th>Bill Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['bill_amount']); ?></td>
                    <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                    <td>
                        <a href="make_payment.php?order_id=<?php echo $row['order_id']; ?>">Make Payment</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No pending payments found.</p>
    <?php endif; ?>
</body>
</html>
