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

// Get the current year
$currentYear = date('Y');

// Query to fetch meal plans for the current year
$sql = "SELECT * FROM meal_plans WHERE YEAR(created_at) = '$currentYear'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Annual Meal Plan Report</title>
    <script>
        function printReport() {
            window.print();
        }
    </script>
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

        <div class="main-content">
        <div class="header2">
            <img src="images/header.png" alt="Header Image">
        </div>

        <section>
            <h2 style="margin-top:20px;">Annual Meal Plan Report</h2>
            <div class="breadcrumbs">
                <a href="view_meal_plans.php" class="breadcrumb-item">
                    <i class="fas fa-utensils"></i> View Meal plan
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="dashboard.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="monthly_report.php" class="breadcrumb-item">
                    <i class="fas fa-file-alt"></i> Monthly Report
                </a>
            </div>

            <?php if ($result->num_rows > 0): ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Meal Name</th>
                            <th>Description</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['meal_name']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['date']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table><br><br>
            <?php else: ?>
                <p>No meal plans for this year.</p><br><br>
            <?php endif; ?>

            <button class="print-link" onclick="printReport()">Print Report</button>
        </section>
    </div>

      <!-- Footer Section -->
      <div class="footer">
        <img src="images/footer.png" alt="Footer Image">
    </div>
    </div>

    <script>
        function printReport() {
            // Temporarily hide elements for printing
            document.querySelector('.sidebar').style.display = 'none';
            document.querySelector('.breadcrumbs').style.display = 'none';
            document.querySelector('.print-link').style.display = 'none';

            // Trigger the print dialog
            window.print();

            // After printing, restore elements
            window.onafterprint = function () {
                document.querySelector('.sidebar').style.display = 'block';
                document.querySelector('.breadcrumbs').style.display = 'block';
                document.querySelector('.print-link').style.display = 'inline-block';
            };
        }
    </script>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
