<?php
include 'db_connect.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize inputs
    $itemName = mysqli_real_escape_string($conn, $_POST['item_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $quantity = intval($_POST['quantity']);

    // Insert new item into the Inventory table
    $insertQuery = "INSERT INTO Inventory (item_name, category, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssi", $itemName, $category, $quantity);

    if ($stmt->execute()) {
        echo "<script>alert('New inventory item added successfully.'); window.location.href='view_inventory.php';</script>";
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
    <title>Add New Inventory Item</title>
    <link rel="stylesheet" href=" add_inventory.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - Add Inventory Item</h1>
        <p>User: [Matron Name] <a href="logout.php">Logout</a></p>
    </header>

    <section>
        <h2>Add New Inventory Item</h2>
        <form method="POST" action="">
            <label for="item_name">Item Name:</label>
            <input type="text" name="item_name" required>
            <br>
            <label for="category">Category:</label>
            <select name="category" required>
                <option value="food">Food</option>
                <option value="furniture">Furniture</option>
                <option value="cleaning">Cleaning</option>
                <option value="Kitchen">Kitchen</option>
                <option value="other">Other</option>
            </select>
            <br>
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" min="1" required>
            <br>
            <input type="submit" value="Add Item">
        </form>
        
        <div>
        <a href="dashboard.php" class="dashboard-button">Dashboard</a> <!-- New Dashboard Button -->
        <a href="view_inventory.php">Back to Inventory List</a>
</div>
    </section>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
