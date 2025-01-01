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

// Fetch the supplier ID from the URL
$supplierId = $_GET['id'];

// Fetch the supplier details
$supplierQuery = "SELECT * FROM Suppliers WHERE supplier_id = $supplierId";
$supplierResult = $conn->query($supplierQuery);
$supplier = $supplierResult->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated form data
    $supplierName = $_POST['supplier_name'];
    $category = $_POST['category'];
    $contact = $_POST['contact'];

    // Update the supplier in the database
    $updateQuery = "UPDATE Suppliers SET supplier_name = '$supplierName', category = '$category', contact = '$contact' WHERE supplier_id = $supplierId";
    if ($conn->query($updateQuery) === TRUE) {
        $_SESSION['message'] = "Supplier updated successfully!";
        header("Location: edit_supplier.php?id=$supplierId"); // Redirect to the same page to show the message
        exit();
    } else {
        echo "Error: " . $updateQuery . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Supplier</title>
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

    <!-- Main Content -->
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

        <section class="edit-section">
            <h2 style="margin-top:20px;">Edit Supplier</h2>

            <!-- Breadcrumbs -->
            <div class="breadcrumbs">
                <a href="view_suppliers.php" class="breadcrumb-item">
                    <i class="fas fa-truck"></i> View Suppliers
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="dashboard.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>

            <!-- Notification Section -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="notification success">
                    <?php 
                        echo $_SESSION['message']; 
                        unset($_SESSION['message']); // Clear the message after displaying
                    ?>
                </div>
            <?php endif; ?>

            <!-- Form Section -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="supplier_name">Supplier Name:</label>
                    <input type="text" name="supplier_name" value="<?php echo $supplier['supplier_name']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="category">Category:</label>
                    <select name="category" required>
                        <option value="food" <?php if($supplier['category'] == 'food') echo 'selected'; ?>>Food</option>
                        <option value="furniture" <?php if($supplier['category'] == 'furniture') echo 'selected'; ?>>Furniture</option>
                        <option value="utilities" <?php if($supplier['category'] == 'utilities') echo 'selected'; ?>>Utilities</option>
                        <option value="repair_services" <?php if($supplier['category'] == 'repair_services') echo 'selected'; ?>>Repair Services</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="contact">Contact:</label>
                    <input type="text" name="contact" value="<?php echo $supplier['contact']; ?>" required>
                </div>

                <button type="submit" class="update-button">Update Supplier</button>
            </form>
        </section>
    </div>

    <script>
        window.onload = function() {
            const notification = document.querySelector('.notification.success');
            if (notification) {
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 5000);
            }
        };
    </script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
