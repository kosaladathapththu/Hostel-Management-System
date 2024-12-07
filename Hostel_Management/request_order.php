<?php
include 'db_connect.php'; // Include database connection

// Fetch all suppliers to populate the dropdown
$suppliersQuery = "SELECT supplier_id, supplier_name FROM Suppliers";
$suppliersResult = $conn->query($suppliersQuery);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $supplierId = intval($_POST['supplier_id']); // Sanitize supplier ID
    $itemName = htmlspecialchars($_POST['item_name']); // Sanitize item name
    $quantity = intval($_POST['quantity']); // Sanitize quantity

    // Insert directly into Orders table
    $insertQuery = "INSERT INTO Orders (supplier_id, item_name, quantity, status, order_amount, order_date) 
                    VALUES (?, ?, ?, 'requested', 0.00, NOW())"; // Default order amount and status
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param('isi', $supplierId, $itemName, $quantity);

    if ($stmt->execute()) {
        echo "<script>alert('Order requested successfully.');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close(); // Close the statement
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
            <?php while ($row = $suppliersResult->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($row['supplier_id']); ?>">
                    <?php echo htmlspecialchars($row['supplier_name']); ?>
                </option>
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
