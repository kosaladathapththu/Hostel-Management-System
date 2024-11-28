<?php
include 'db_connect.php'; // Include database connection

// Fetch all suppliers to populate the dropdown
$suppliersQuery = "SELECT supplier_id, supplier_name FROM Suppliers";
$suppliersResult = $conn->query($suppliersQuery);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $supplierId = $_POST['supplier_id'];
    $itemName = $_POST['item_name'];
    $quantity = $_POST['quantity'];

    // Insert into Orders table
    $insertQuery = "INSERT INTO Orders (supplier_id, item_name, quantity) VALUES ('$supplierId', '$itemName', '$quantity')";
    if ($conn->query($insertQuery) === TRUE) {
        $orderId = $conn->insert_id;  // Get the ID of the new order

        // Insert into OrderDetails table
        $detailsQuery = "INSERT INTO OrderDetails (order_id, order_amount, delivery_date) 
                         VALUES ('$orderId', '0.00', '0000-00-00')";  // Dummy data
        $conn->query($detailsQuery);

        // Insert into OrderStatus table
        $statusQuery = "INSERT INTO OrderStatus (order_id, supplier_acceptance) n
                        VALUES ('$orderId', 'Pending')";
        $conn->query($statusQuery);

        // Insert into OrderPayments table
        $paymentQuery = "INSERT INTO OrderPayments (order_id, payment_status) 
                         VALUES ('$orderId', 'Pending')";
        $conn->query($paymentQuery);

        echo "<script>alert('Order requested successfully.');</script>";
    } else {
        echo "<script>alert('Error: " . $insertQuery . " " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request New Order</title>
    <link rel="stylesheet" href="request_order.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - Request New Order</h1>
        <p>User: [Matron Name] <a href="logout.php">Logout</a></p>
    </header>

    <section>
    <h2>Request a New Order</h2>
    <form method="POST" action="">
        <label for="supplier_id">Supplier:</label>
        <select name="supplier_id" required>
            <?php while($row = $suppliersResult->fetch_assoc()): ?>
                <option value="<?php echo $row['supplier_id']; ?>"><?php echo $row['supplier_name']; ?></option>
            <?php endwhile; ?>
        </select>
        <br>
        <label for="item_name">Item Name:</label>
        <input type="text" name="item_name" required>
        <br>
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" required>
        <br>
        <input type="submit" value="Request Order" class="submit-button">
    </form>
    <div class="button-group">
        <a href="view_order.php" class="back-button">Back to Orders</a>
        <a href="dashboard.php" class="dashboard-button">Dashboard</a>
    </div>
    </section>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
