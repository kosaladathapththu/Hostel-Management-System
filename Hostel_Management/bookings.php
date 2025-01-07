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

// Handle approve or decline action
if (isset($_GET['action']) && isset($_GET['record_id'])) {
    $action = $_GET['action'];
    $record_id = $_GET['record_id'];

    if ($action == 'approve') {
        $status = 'approved';
    } elseif ($action == 'decline') {
        $status = 'declined';
    }

    // Update the status in the database
    $updateQuery = "UPDATE `resident_checking-checkouts` SET `status` = ? WHERE `checking-checkout_id` = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $status, $record_id);
    $stmt->execute();
}

// SQL query to fetch check-in/check-out details with status
$query = "
SELECT 
    cc.`checking-checkout_id` AS record_id,
    r.`resident_name`,
    cc.`resident_id`,
    cc.`check_in_date`,
    cc.`check_out_date`,
    cc.`status`
FROM `resident_checking-checkouts` cc
LEFT JOIN `Residents` r ON cc.`resident_id` = r.`id`
ORDER BY cc.`created_at` DESC"; // Ordered by creation date

// Prepare the query
$stmt = $conn->prepare($query);

// Check if the statement is prepared successfully
if (!$stmt) {
    die("SQL error: " . $conn->error); // Detailed error if statement fails
}

// Execute the query
$stmt->execute();

// Fetch the results
$checkInOutResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Check-ins/Outs</title>
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
            <h2 style="margin-top:20px;">Resident Check-in/Check-out History</h2>
            <div class="breadcrumbs">
                <a href="add_booking.php" class="breadcrumb-item">
                    <i class="fas fa-plus"></i> Add Checking/checkouts
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="dashboard.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Record ID</th>
                        <th>Resident ID</th>
                        <th>Resident Name</th>
                        <th>Check-in Date</th>
                        <th>Check-out Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $checkInOutResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['record_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['resident_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['resident_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['check_in_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['check_out_date']); ?></td>
                            <td>
                                <?php if ($row['status'] === 'approved'): ?>
                                    <span style="color: green;"><?php echo htmlspecialchars($row['status']); ?></span>
                                <?php elseif ($row['status'] === 'declined'): ?>
                                    <span style="color: red;"><?php echo htmlspecialchars($row['status']); ?></span>
                                <?php else: ?>
                                    <span style="color: gray;"><?php echo htmlspecialchars($row['status']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'Checked-In'): ?>
                                    <a href="?action=approve&record_id=<?php echo $row['record_id']; ?>" class="approve-btn">Approve</a> |
                                    <a href="?action=decline&record_id=<?php echo $row['record_id']; ?>" class="decline-btn">Decline</a>
                                <?php endif; ?>
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
// Close statement and connection
$stmt->close();
$conn->close();
?>
