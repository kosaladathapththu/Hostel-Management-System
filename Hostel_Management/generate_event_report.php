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

// Check if the report type (monthly or annually) is set
$reportType = isset($_GET['type']) ? $_GET['type'] : '';

if ($reportType == 'annually') {
    // Query for annual event report with event names
    $reportQuery = "
    SELECT YEAR(start_date) AS year, 
           GROUP_CONCAT(CONCAT_WS(': ', DATE_FORMAT(start_date, '%Y-%m-%d'), title) SEPARATOR '<br>') AS event_list, 
           COUNT(event_id) AS total_events 
    FROM Events 
    GROUP BY year 
    ORDER BY year";
} elseif ($reportType == 'monthly' && isset($_GET['year'])) {
    // Get the year for monthly reports
    $selectedYear = intval($_GET['year']);
    
    // Query for monthly event report for a specific year with event names and dates
    $reportQuery = "
    SELECT MONTH(start_date) AS month, 
           GROUP_CONCAT(CONCAT_WS(': ', DATE_FORMAT(start_date, '%Y-%m-%d'), title) SEPARATOR '<br>') AS event_list, 
           COUNT(event_id) AS total_events 
    FROM Events 
    WHERE YEAR(start_date) = $selectedYear 
    GROUP BY month 
    ORDER BY month";
} else {
    echo "Invalid report type or missing parameters.";
    exit;
}

$reportResult = $conn->query($reportQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo ucfirst($reportType); ?> Event Report</title>
    <link rel="stylesheet" href="stylesresident.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Print-specific CSS -->
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
    .sidebar, .button-container, .breadcrumb-item {
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
        <!-- Header Section -->
    <div class="header">
        <img src="images/header.png" alt="Header Image">
    </div>

        <section>
            <h2 style="margin-top:20px;"><?php echo ucfirst($reportType); ?> Event Report</h2>
            
            <div class="breadcrumbs">
            <a href="view_calendar.php" class="breadcrumb-item"><i class="fas fa-backward"></i>  Back to calander</a>
            <span class="breadcrumb-separator">|</span>
                <a href="dashboard.php" class="breadcrumb-item"><i class="fas fa-home"></i> Dashboard</a>
                <span class="breadcrumb-separator">|</span>
            </div>

            <table class="report-table">
                <thead>
                    <tr>
                        <th><?php echo ($reportType == 'annually') ? 'Year' : 'Month'; ?></th>
                        <th>Total Events</th>
                        <th>Event List</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $reportResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo ($reportType == 'annually') ? $row['year'] : $row['month']; ?></td>
                            <td><?php echo $row['total_events']; ?></td><br>
                            <td><?php echo $row['event_list']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table><br><br>

            <center><div class="button-container">
                <button class="print-link" onclick="window.print()">Print Report</button>
            </div></center>
        </section>
        <!-- Footer Section -->
    <div class="footer">
        <img src="images/footer.png" alt="Footer Image">
    </div>
    </div>

    <script>
        function toggleDetails(month) {
            const details = document.getElementById('details-' + month);
            if (details.style.display === 'none' || details.style.display === '') {
                details.style.display = 'table-row';
            } else {
                details.style.display = 'none';
            }
        }
    </script>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>

