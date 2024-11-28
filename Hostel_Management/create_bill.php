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

    // Prepare the SQL statement to insert the bill
    $billInsertSQL = "INSERT INTO Bill (order_id, bill_amount, bill_date, bill_status) 
                      VALUES (?, ?, ?, 'Pending')";
    $billStmt = $conn->prepare($billInsertSQL);

    // Check if the statement prepared successfully
    if (!$billStmt) {
        die("Error preparing statement for bill: " . $conn->error);
    }

    // Bind parameters (i: integer, d: double/float, s: string)
    $billStmt->bind_param("ids", $order_id, $bill_amount, $bill_date);

    // Execute the query to insert the bill
    if ($billStmt->execute()) {
        // Update the order's status to 'Accepted'
        $orderUpdateSQL = "UPDATE Orders SET status = 'approvedss' WHERE order_id = ?";
        $orderStmt = $conn->prepare($orderUpdateSQL);

        if ($orderStmt) {
            $orderStmt->bind_param("i", $order_id);
            if ($orderStmt->execute()) {
                echo "<script>alert('Bill created and order accepted successfully!');</script>";
            } else {
                echo "<script>alert('Error: Could not update order status.');</script>";
            }
            $orderStmt->close();
        } else {
            echo "<script>alert('Error preparing statement for order update: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error: Could not create bill.');</script>";
    }

    // Close the prepared statement
    $billStmt->close();
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
        <p><a href="supplier_dashboard.php">Back to Supplier Dashboard</a></p>
    </header>

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
