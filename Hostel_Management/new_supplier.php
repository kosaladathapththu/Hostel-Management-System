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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="new_supplier.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Supplier Panel</h2>
        <div class="profile-info">
        <p>Welcome, <?php echo htmlspecialchars($supplier['username']); ?></p>
        </div>
        <ul>
            <li><a href="view_orders.php"><i class="fas fa-users"></i> Order management</a></li>
            <li><a href="view_contracts.php"><i class="fas fa-bars"></i>Supplier contracts</a></li><br>

        <button onclick="window.location.href='edit_supplier_profile.php'" class="edit-btn"><i class="fas fa-user-edit"></i> Edit Profile</button>
        <button onclick="window.location.href='supplier_logout.php'" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
    <header>
        <div class="header-left">
            <img src="The_Salvation_Army.png" alt="Logo" class="logo">

        </div>
        <center><b><h1>Salvation army Girsl hostel</h1></b></center>
        <div class="header-right">
            <p>Welcome, <?php echo ($supplier['username']); ?></p>
        </div>
    </header>
        <section class="dashboard-section">
            
            <div class="dashboard-box">
                <h2><i class="fas fa-users"></i> Order Management</h2>
                <button onclick="window.location.href='supplier_dashboard.php'" class="control-btn">View orders</button>

            </div>
            <div class="dashboard-box">
                <h2><i class="fas fa-bars"></i> Supplier contracts</h2>
                <button onclick="window.location.href='view_supplier_contracts.php'" class="control-btn">View contract details</button>

            </div>

        </section>
    </div>
</body>
</html>
