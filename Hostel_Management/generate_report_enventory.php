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

// Get the report type from the GET request
$reportType = isset($_GET['report_type']) ? $_GET['report_type'] : '';

// Set the date range based on report type
if ($reportType === 'monthly') {
    $startDate = date("Y-m-01"); // First day of current month
    $endDate = date("Y-m-t"); // Last day of current month
} elseif ($reportType === 'yearly') {
    $startDate = date("Y-01-01"); // First day of current year
    $endDate = date("Y-12-31"); // Last day of current year
} else {
    echo "Invalid report type selected.";
    exit();
}

// Check database connection
if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch inventory data within the specified date range
$query = "
    SELECT item_id, item_name, category, quantity, item_price, last_updated 
    FROM inventory 
    WHERE last_updated BETWEEN ? AND ?
    ORDER BY category";
$stmt = $conn->prepare($query);

// Check if the query was prepared successfully
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

// Bind parameters and execute query
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo ucfirst($reportType); ?> Inventory Report</title>
    <link rel="stylesheet" href="report.css">
    <link rel="stylesheet" href="stylesresident.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
/* Ensure full height layout */
@media print {
    header {
        position: fixed;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 0;
        object-fit: cover;

    }

    footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        margin: 0;
        padding: 0;
        object-fit: bottom;

    }

    /* Hide the sidebar and print button */
    .sidebar, .breadcrumb-item,print-link {
        display: none;
    }
    
   
}

</style>
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
        <div class="main-content">
        <div class="header2">
            <img src="images/header.png" alt="Header Image">
        </div>

        <section>
            <h2><?php echo ucfirst($reportType); ?> Inventory Report</h2><br>
            <p>Report Period: <?php echo $startDate; ?> to <?php echo $endDate; ?></p>

            <div class="breadcrumbs">
                <a href="dashboard.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="view_inventory.php" class="breadcrumb-item">
                    <i class="fas fa-boxes"></i> View Inventory
                </a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['item_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($row['category'] ?: 'N/A')); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo number_format($row['item_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['last_updated']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="actions">
                
                    
                    <br><br>
                    <button class="print-link" onclick="printReport()">Print Report</button>

                </form>     
            </div>

            <!-- Footer Section -->
      <div class="footer">
        <img src="images/footer.png" alt="Footer Image">
    </div>
    </div>
        </section>
    </div>

    <script>
        function printReport() {
    // Temporarily hide elements for printing
    document.querySelector('.sidebar').style.display = 'none'; // Hides the sidebar
    document.querySelector('.breadcrumbs').style.display = 'none'; // Hides the breadcrumbs
    document.querySelector('.print-link').style.display = 'none'; // Hides the Print Report button

    // Trigger the print dialog
    window.print();

    // After printing, restore elements
    window.onafterprint = function () {
        document.querySelector('.sidebar').style.display = 'block'; // Shows the sidebar again
        document.querySelector('.breadcrumbs').style.display = 'block'; // Shows the breadcrumbs again
        document.querySelector('.print-link').style.display = 'inline-block'; // Shows the print button again
    };
}


    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
