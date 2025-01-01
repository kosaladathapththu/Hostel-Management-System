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
$ordersQuery = "SELECT * FROM orders WHERE supplier_id = ? ORDER BY order_id DESC";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Dashboard</title>
    <link rel="stylesheet" href="new_supplier.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>

    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Supplier Panel</h2>
        <div class="profile-info">
            <p>Welcome, <?php echo htmlspecialchars($supplier['username']); ?></p>
        </div>
        <ul>
            <li><a href="new_supplier.php"><i class="fas fa-home"></i> Dashboard</a></li><br>
            <li><a href="supplier_dashboard.php"><i class="fas fa-users"></i> Order Management</a></li><br>
            <li><a href="view_supplier_contracts.php"><i class="fas fa-bars"></i> Supplier Contracts</a></li><br>

            <button onclick="window.location.href='edit_supplier_profile.php'" class="edit-btn"><i class="fas fa-user-edit"></i> Edit Profile</button>
            <button onclick="window.location.href='supplier_logout.php'" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div class="header-left">
                <img src="The_Salvation_Army.png" alt="Logo" class="logo">
            </div>
            <center><b><h1>Salvation Army Girls Hostel</h1></b></center>
            <div class="header-right">
                <p>Welcome, <?php echo htmlspecialchars($supplier['username']); ?></p>
            </div>
        </header>

        <section class="dashboard-section">
        <h2 style="margin-top:20px;">View Orders</h2>
            <div class="breadcrumbs">
                <a href="new_supplier.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
              
            </div>

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
                                    <a href="accept_order.php?order_id=<?php echo $row['order_id']; ?>" class="accept-btn">Accept</a>
                                    <a href="decline_order.php?order_id=<?php echo $row['order_id']; ?>" class="decline-btn">Decline</a>
                                <?php elseif ($row['status'] == 'Paid'): ?>
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

        </section>
    </div>
</body>
</html>

<?php
$stmt->close();
$ordersStmt->close();
$conn->close();
?>
