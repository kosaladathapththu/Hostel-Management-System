<?php
include 'db_connect.php'; // Include database connection

// Check if a search term is provided
$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
    
    // Query to search for items by name, category, or other fields
    $inventoryQuery = "
    SELECT * FROM Inventory 
    WHERE item_name LIKE '%$searchTerm%' 
    OR category LIKE '%$searchTerm%' 
    ORDER BY category";
} else {
    // Default query to show all inventory items
    $inventoryQuery = "SELECT * FROM Inventory ORDER BY category";
}

$inventoryResult = $conn->query($inventoryQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Inventory</title>
    <link rel="stylesheet" href="view_inventory.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - Inventory</h1>
        <p>User: [Matron Name] <a href="logout.php">Logout</a></p>
    </header>

    <section>
        <h2>Inventory List</h2>

        <!-- Search Form -->
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search by item name or category" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit">Search</button>
        </form> <br>

        <!-- Report Generation Buttons -->
        <form method="GET" action="generate_report_enventory.php" class="report-buttons">
            <button type="submit" name="report_type" value="monthly">Generate Monthly Report</button>
            <button type="submit" name="report_type" value="yearly">Generate Yearly Report</button>
        </form>

        <table class="inventory-table">
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Item Price</th> <!-- New Column -->
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($inventoryResult->num_rows > 0): ?>
                    <?php while ($row = $inventoryResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['item_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($row['category'])); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['item_price']); ?></td> <!-- New Column -->
                            <td><?php echo htmlspecialchars($row['last_updated']); ?></td>
                            <td>
                                <a href="edit_inventory.php?id=<?php echo $row['item_id']; ?>" class="edit-button">Edit</a>
                                <a href="delete_inventory.php?id=<?php echo $row['item_id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No inventory items found.</td> <!-- Adjust colspan -->
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="add_inventory.php" class="add-button">Add New Item</a>
        <br><br>
        <a href="dashboard.php" class="dashboard-button">Dashboard</a> 
    </section>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
