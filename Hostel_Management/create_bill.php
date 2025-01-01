<?php
include 'db_connect.php';
session_start();

// Ensure the supplier is logged in
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

// Check if order_id is provided in the URL
if (isset($_GET['order_id'])) {
    $order_id = (int)$_GET['order_id'];
} else {
    die("Error: Order ID not provided.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $bill_amount = (float)$_POST['bill_amount'];
    $bill_date = $_POST['bill_date']; // String date

    // Update the Orders table with the new details
    $updateOrderSQL = "UPDATE orders 
                       SET bill_amount = ?, 
                           status = 'Bill Assigned', 
                           delivery_date = ? 
                       WHERE order_id = ?";
    $updateOrderStmt = $conn->prepare($updateOrderSQL);

    // Check if the statement prepared successfully
    if (!$updateOrderStmt) {
        die("Error preparing statement for order update: " . $conn->error);
    }

    // Bind parameters
    $updateOrderStmt->bind_param("dsi", $bill_amount, $bill_date, $order_id);

    // Execute the query to update the Orders table
    if ($updateOrderStmt->execute()) {
        echo "<script>alert('Order updated with bill details successfully!');</script>";
        echo "<script>window.location.href='supplier_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: Could not update order with bill details.');</script>";
    }

    $updateOrderStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Bill</title>
    <link rel="stylesheet" href="new_supplier.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        <nav class="breadcrumb">
            <a href="new_supplier.php">Dashboard</a>
            <span>&gt;</span>
            <a href="supplier_dashboard.php">Orders</a>
            <span>&gt;</span>
            <span>Create Bill</span>
        </nav>


            <i><h2 style="margin-left:20px;">Create Bill for the Order ID; <?php echo htmlspecialchars($order_id); ?>
            </h2></i>

            <form method="POST" action="">
                <label for="bill_amount">Bill Amount:</label><br>
                <input type="number" name="bill_amount" id="bill_amount" required step="0.01" placeholder="Enter bill amount">
                <br>
                <label for="bill_date">Delivery Date:</label><br>
                <input type="date" name="bill_date" id="bill_date" required>
                <br><br>
                <input type="submit" value="Send" class="submit-button"><br>
                
            </form>

      
        <center>
            <img src="create_bill.png" alt="invoice image" style="width: 400px; height: auto; box-shadow: 10px 10px 8px 10px rgba(0, 0, 0, 0.2); margin-top: 10px; border-radius: 1000px;">

</center>

 
    </div>
</body>
</html>
