<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['supplier_id'])) {
    header("Location: supplier_login.php");
    exit();
}

$supplier_id = $_SESSION['supplier_id'];

// Fetch supplier's information
$supplierQuery = "SELECT username FROM suppliers WHERE supplier_id = ?";
$stmt = $conn->prepare($supplierQuery);
$stmt->bind_param("i", $supplier_id);
$stmt->execute();
$supplierResult = $stmt->get_result();
$supplier = $supplierResult->fetch_assoc();

// Fetch supplier's orders
$ordersQuery = "SELECT * FROM orders WHERE supplier_id = ?";
$ordersStmt = $conn->prepare($ordersQuery);
$ordersStmt->bind_param("i", $supplier_id);
$ordersStmt->execute();
$ordersResult = $ordersStmt->get_result();

// Check if any orders exist for this supplier
$orderCount = $ordersResult->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supplier Dashboard</title>
    <link rel="stylesheet" href="supplier_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Styling for the Print button */
        .print-btn {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .print-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    
<header>
    <h1>Salvation Army Girls Hostel - Supplier Dashboard</h1>
    <div class="user-info">
        <p>Welcome, <?php echo htmlspecialchars($supplier['username']); ?></p>
        <a href="edit_supplier_profile.php" class="edit-btn">Edit Profile</a>
        <a href="supplier_logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<h2>Your Orders</h2>

<?php if ($orderCount > 0): ?>
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
                <?php if ($row['status'] == 'requested'): ?>
                    <a href="accept_order.php?order_id=<?php echo $row['order_id']; ?>">Accept</a>
                    <a href="decline_order.php?order_id=<?php echo $row['order_id']; ?>">Decline</a>
                <?php elseif ($row['status'] == 'Paid'): ?>
                    <!-- Display Print Button for Paid Orders -->
                    <a href="print_receipt.php?order_id=<?php echo $row['order_id']; ?>" target="_blank">
                        <button class="print-btn">Print Receipt</button>
                    </a>
                <?php else: ?>
                    <?php echo ucfirst($row['status']); ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No orders found for you.</p>
<?php endif; ?>

</body>
</html>

<?php
$stmt->close();
$ordersStmt->close();
$conn->close();
?>
