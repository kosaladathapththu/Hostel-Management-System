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
    <link rel="stylesheet" href="stylesresident.css">
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
            <h2 style="margin-top:20px;">Request a New Order</h2>
            <div class="breadcrumbs">
                <a href="view_order.php" class="breadcrumb-item">
                    <i class="fas fa-receipt"></i> View Order List
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="dashboard.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>

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


        </section>
    </div>
</body>
</html>

<?php
$conn->close(); 
?>
