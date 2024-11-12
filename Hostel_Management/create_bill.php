<?php
include 'db_connect.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $bill_amount = $_POST['bill_amount'];
    $bill_date = $_POST['bill_date'];
    $balance = $_POST['balance'];

    // Insert the bill into the Bill table
    $sql = "INSERT INTO Bill (order_id, bill_amount, bill_date, balance, bill_status) 
            VALUES (?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iddd", $order_id, $bill_amount, $bill_date, $balance);

    if ($stmt->execute()) {
        echo "<script>alert('Bill created successfully!');</script>";

        // Redirect back to the View Orders page
        header("Location: view_order.php");
        exit();
    } else {
        echo "<script>alert('Error: Could not create the bill.');</script>";
    }

    $stmt->close();
}

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
        <p><a href="view_order.php">Back to Orders</a></p>
    </header>

    <section>
        <h2>Enter Bill Details</h2>
        <form method="POST" action="">
            <label for="bill_amount">Bill Amount:</label>
            <input type="number" name="bill_amount" required step="0.01">
            <br>
            <label for="bill_date">Bill Date:</label>
            <input type="date" name="bill_date" required>
            <br>
            <label for="balance">Balance:</label>
            <input type="number" name="balance" required step="0.01">
            <br>
            <input type="submit" value="Create Bill" class="submit-button">
        </form>
    </section>
</body>
</html>
