<?php
include 'db_connect.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $amount = $_POST['amount'];
        $delivery_date = $_POST['delivery_date'];

        // Update the Orders table (since there's no OrderDetails table)
        $query = "UPDATE Orders SET status = 'approved' WHERE order_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $order_id);
        if ($stmt->execute()) {
            // Insert into the Bill table
            $billQuery = "INSERT INTO Bill (order_id, bill_amount, delivery_date) VALUES (?, ?, ?)";
            $billStmt = $conn->prepare($billQuery);
            $billStmt->bind_param("ids", $order_id, $amount, $delivery_date);
            if ($billStmt->execute()) {
                echo "<script>alert('Order details submitted and bill created successfully.');</script>";
                header("Location: supplier_dashboard.php");
                exit();
            } else {
                echo "<script>alert('Error creating bill.');</script>";
            }
        } else {
            echo "<script>alert('Error updating order status.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supplier Input</title>
</head>
<body>
    <h1>Input Order Details</h1>
    <form method="POST" action="">
        <label for="amount">Order Amount:</label>
        <input type="number" name="amount" step="0.01" required>
        <br>
        <label for="delivery_date">Delivery Date:</label>
        <input type="date" name="delivery_date" required>
        <br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
