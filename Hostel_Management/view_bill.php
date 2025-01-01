<?php
include 'db_connect.php';

if (isset($_GET['bill_id'])) {
    $bill_id = $_GET['bill_id'];

    $query = "SELECT * FROM Bill WHERE bill_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bill_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bill = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Bill</title>
</head>
<body>
    <h1>Bill Details</h1>
    <p>Bill ID: <?php echo $bill['bill_id']; ?></p>
    <p>Order ID: <?php echo $bill['order_id']; ?></p>
    <p>Bill Amount: <?php echo $bill['bill_amount']; ?></p>
    <p>Amount Given: <?php echo $bill['amount_given']; ?></p>
    <p>Status: <?php echo ucfirst($bill['bill_status']); ?></p>
</body>
</html>
