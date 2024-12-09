<?php
include 'db_connect.php';

session_start();

// Ensure the supplier is logged in
if (!isset($_SESSION['supplier_id'])) {
    header("Location: supplier_login.php");
    exit();
}

$supplier_id = $_SESSION['supplier_id'];

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
    $updateOrderSQL = "UPDATE Orders 
                       SET bill_amount = ?, 
                           status = 'Bill Assigned',
                           delivery_date = ? 
                       WHERE order_id = ?";
    $updateOrderStmt = $conn->prepare($updateOrderSQL);

    // Check if the statement prepared successfully
    if (!$updateOrderStmt) {
        die("Error preparing statement for order update: " . $conn->error);
    }

    // Bind parameters (d: double/float, s: string, i: integer)
    $updateOrderStmt->bind_param("dsi", $bill_amount, $bill_date, $order_id);

    // Execute the query to update the Orders table
    if ($updateOrderStmt->execute()) {
        echo "<script>alert('Order updated with bill details successfully!');</script>";
        echo "<script>window.location.href='supplier_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: Could not update order with bill details.');</script>";
    }

    // Close the prepared statement
    $updateOrderStmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Bill</title>
    <link rel="stylesheet" href="create_bill.css">
</head>
<body>
    <header>
        <h1>Create Bill for Order</h1>
        <p><a href="supplier_dashboard.php">Back to Supplier Orders</a></p>
    </header>

    <nav class="breadcrumb">
        <a href="supplier_dashboard.php">Supplier Dashboard</a>
        <span>&gt;</span>
        <a href="order_list.php">Orders</a>
        <span>&gt;</span>
        <span>Create Bill</span>
    </nav>

    <section>
        <h2>Enter Bill Details</h2>
        <form method="POST" action="">
            <label for="bill_amount">Bill Amount:</label>
            <input type="number" name="bill_amount" id="bill_amount" required step="0.01">
            <br>
            <label for="bill_date">Bill Date:</label>
            <input type="date" name="bill_date" id="bill_date" required>
            <br>
            <input type="submit" value="Send" class="submit-button">
        </form>
    </section>
</body>

</html>
