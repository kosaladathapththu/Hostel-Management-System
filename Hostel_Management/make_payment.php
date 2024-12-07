<?php
include 'db_connect.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
} else {
    die("Order ID is required.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_amount = (float)$_POST['payment_amount'];
    $payment_date = $_POST['payment_date'];

    $updatePaymentSQL = "UPDATE orderpayments 
                         SET payment_amount = ?, payment_date = ?, payment_status = 'Paid' 
                         WHERE order_id = ?";
    $stmt = $conn->prepare($updatePaymentSQL);
    $stmt->bind_param("dsi", $payment_amount, $payment_date, $order_id);

    if ($stmt->execute()) {
        $updateOrderSQL = "UPDATE orders 
                           SET status = 'Paid' 
                           WHERE order_id = ?";
        $orderStmt = $conn->prepare($updateOrderSQL);
        $orderStmt->bind_param("i", $order_id);
        $orderStmt->execute();

        echo "<script>alert('Payment successful!');</script>";
        header("Location: matron_view_payments.php");
    } else {
        echo "<script>alert('Error processing payment.');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Make Payment</title>
</head>
<body>
    <h1>Make Payment</h1>
    <form method="POST">
        <label for="payment_amount">Payment Amount:</label>
        <input type="number" id="payment_amount" name="payment_amount" step="0.01" required>
        <br>
        <label for="payment_date">Payment Date:</label>
        <input type="date" id="payment_date" name="payment_date" required>
        <br>
        <input type="submit" value="Pay">
    </form>
</body>
</html>
