<?php
session_start(); // Start session for authentication check

// Check if the user is logged in as a matron
if (!isset($_SESSION['matron_id'])) {
    header("Location: matron_auth.php"); // Redirect if not logged in
    exit();
}

include 'db_connect.php'; // Include database connection

// Fetch the matron's details (Extra safety check)
$matron_id = $_SESSION['matron_id'];
$matronQuery = "SELECT first_name FROM Matrons WHERE matron_id = ?";
$stmt = $conn->prepare($matronQuery);
$stmt->bind_param("i", $matron_id);
$stmt->execute();
$matronResult = $stmt->get_result();

if ($matronResult->num_rows === 0) {
    header("Location: matron_auth.php"); // Redirect if matron not found
    exit();
}

// Assign matron's first name
$matronData = $matronResult->fetch_assoc();
$matron_first_name = $matronData['first_name'];

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
    <link rel="stylesheet" href="stylesresident.css"> <!-- Link to your CSS file -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Matron Panel</h2>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="residents.php"><i class="fas fa-users"></i> Residents</a></li>
            <li><a href="bookings.php"><i class="fas fa-calendar-check"></i> Check-ins/Outs</a></li>
            <li><a href="rooml.php"><i class="fas fa-door-open"></i> Rooms</a></li>
            <li><a href="payments.php"><i class="fas fa-money-check-alt"></i> Payments</a></li>
            <li><a href="view_suppliers.php"><i class="fas fa-truck"></i> Suppliers</a></li>
            <li><a href="view_order.php"><i class="fas fa-receipt"></i> Orders</a></li>
            <li><a href="view_inventory.php"><i class="fas fa-boxes"></i> Inventory</a></li>
            <li><a href="view_calendar.php"><i class="fas fa-calendar"></i> Events</a></li>
            <li><a href="view_meal_plans.php"><i class="fas fa-utensils"></i> Meal Plans</a></li>
        </ul>
        <button onclick="window.location.href='matron_logout.php'" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>

    <div class="main-content">
        <header>
            <div class="header-left">
                <img src="The_Salvation_Army.png" alt="Logo" class="logo">
            </div>
            <center><b><h1>Salvation Army Girls Hostel</h1></b></center>
            <div class="header-right">
                <p>Welcome, <?php echo htmlspecialchars($matron_first_name); ?></p>
            </div>
        </header>

        <section>
            <h2>Inventory List</h2>

            <div class="breadcrumbs">
                <a href="add_inventory.php" class="breadcrumb-item">
                    <i class="fas fa-plus"></i> Add New Item
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="dashboard.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="view_order.php" class="breadcrumb-item">
                    <i class="fas fa-receipt"></i> Orders
                </a>
            </div>
<table>
    <tr>
        <td>
            <!-- Search Form -->
            <form method="GET" action="" >
                <input type="text" name="search" placeholder="Search by item name or category" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" style="width:50%; background-color: #bfc9ca; color: black; padding: 8px 16px; font-size: 14px; border: none; border-radius: 10px; cursor: pointer; margin-right: 30px; margin-top: 30px; float: right;">Search</button>

            </form><br>

</td>
<td>
            <!-- Report Generation Buttons -->
            <form method="GET" action="generate_report_enventory.php" class="report-buttons">
            <center>
    <button type="submit" style="width:70%; background-color:#57dfec; color: white; padding: 8px 16px; font-size: 14px; border: none; border-radius: 10px; cursor: pointer; box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.3);" name="report_type" value="monthly">Generate Monthly Report</button>
    <button type="submit" style="width:70%; background-color:#57dfec; color: white; padding: 8px 16px; font-size: 14px; border: none; border-radius: 10px; cursor: pointer; box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.3);" name="report_type" value="yearly">Generate Yearly Report</button>
</center>

</center>
            </form>
</td>
            </tr>

            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Item Price</th>
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
                                <td><?php echo htmlspecialchars($row['item_price']); ?></td>
                                <td><?php echo htmlspecialchars($row['last_updated']); ?></td>
                                <td>
                                    <a href="edit_inventory.php?id=<?php echo $row['item_id']; ?>" class="edit-btn">Edit</a>
                                    <a href="delete_inventory.php?id=<?php echo $row['item_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No inventory items found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
