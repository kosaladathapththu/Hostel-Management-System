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

// Fetch all suppliers
$suppliersQuery = "SELECT * FROM Suppliers";
$suppliersResult = $conn->query($suppliersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Suppliers</title>
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
            <h2 style="margin-top:20px;">Supplier List</h2>
            <div class="breadcrumbs">
                <a href="add_supplier.php" class="breadcrumb-item">
                    <i class="fas fa-plus"></i> Add new Supplier
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="dashboard.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>

            </div>

            <table>
                <thead>
                    <tr>
                        <th>Supplier ID</th>
                        <th>Supplier Name</th>
                        <th>Category</th>
                        <th>Contact</th>
                        <th>Rate Supplier</th> 
                        <th>Actions</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $suppliersResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['supplier_id']; ?></td>
                            <td><?php echo $row['supplier_name']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td><?php echo $row['contact']; ?></td>
                            <td>
                                <a href="rate_supplier.php?id=<?php echo $row['supplier_id']; ?>" class="rate-btn">Rate Supplier</a>
                            </td>
                            <td>
                                <!-- Edit Button -->
                                <a href="edit_supplier.php?id=<?php echo $row['supplier_id']; ?>" class="edit-btn">Edit</a>
                                <!-- Delete Button -->
                                <a href="delete_supplier.php?id=<?php echo $row['supplier_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this supplier?');">Delete</a>
                            </td>
                           
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>


        </section>
    </div>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
