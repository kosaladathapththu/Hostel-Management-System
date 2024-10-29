<?php
include 'db_connect.php'; // Include database connection

// Fetch the item ID from the URL
$itemId = intval($_GET['id']);

// Fetch the current item details
$itemQuery = "SELECT * FROM Inventory WHERE item_id = ?";
$stmt = $conn->prepare($itemQuery);
$stmt->bind_param("i", $itemId);
$stmt->execute();
$itemResult = $stmt->get_result();
$item = $itemResult->fetch_assoc();
$stmt->close();

if (!$item) {
    echo "Item not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated form data and sanitize inputs
    $itemName = mysqli_real_escape_string($conn, $_POST['item_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $quantity = intval($_POST['quantity']);

    // Update the item in the database
    $updateQuery = "UPDATE Inventory SET item_name = ?, category = ?, quantity = ? WHERE item_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssii", $itemName, $category, $quantity, $itemId);

    if ($stmt->execute()) {
        echo "<script>alert('Inventory item updated successfully.'); window.location.href='view_inventory.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close(); // Close the prepared statement
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Inventory Item</title>
    <link rel="stylesheet" href="edit_inventory.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - Edit Inventory Item</h1>
        <p>User: [Matron Name] <a href="logout.php">Logout</a></p>
    </header>

    <section>
        <h2>Edit Inventory Item</h2>
        <form method="POST" action="">
            <label for="item_name">Item Name:</label>
            <input type="text" name="item_name" value="<?php echo htmlspecialchars($item['item_name']); ?>" required>
            <br>
            <label for="category">Category:</label>
            <select name="category" required>
                <option value="food" <?php if($item['category'] == 'food') echo 'selected'; ?>>Food</option>
                <option value="furniture" <?php if($item['category'] == 'furniture') echo 'selected'; ?>>Furniture</option>
                <option value="cleaning" <?php if($item['category'] == 'cleaning') echo 'selected'; ?>>Cleaning</option>
                <option value="Kitchen" <?php if($item['category'] == 'Kitchen') echo 'selected'; ?>>Kitchen</option>
                <option value="other" <?php if($item['category'] == 'other') echo 'selected'; ?>>Other</option>
            </select>
            <br>
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" min="1" value="<?php echo htmlspecialchars($item['quantity']); ?>" required>
            <br>
            <input type="submit" value="Update Item">
        </form>
        
        <a href="dashboard.php" class="dashboard-button">Dashboard</a> <!-- New Dashboard Button -->
        <a href="view_inventory.php">Back to Inventory List</a>
    </section>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
