<?php
include 'db_connect.php'; // Include database connection

// Fetch total residents
$totalResidentsQuery = "SELECT COUNT(*) as total FROM Residents";
$totalResidentsResult = $conn->query($totalResidentsQuery);
$totalResidents = $totalResidentsResult->fetch_assoc()['total'];

// Fetch total room capacity
$totalRoomCapacityQuery = "SELECT SUM(capacity) as total_capacity FROM Rooms WHERE status = 'available'";
$totalRoomCapacityResult = $conn->query($totalRoomCapacityQuery);
$totalRoomCapacity = $totalRoomCapacityResult->fetch_assoc()['total_capacity'];

// Fetch current residents in available rooms
$currentResidentsQuery = "SELECT SUM((SELECT COUNT(*) FROM Residents r WHERE r.room_id = Rooms.room_id)) as total_residents 
                          FROM Rooms WHERE status = 'available'";
$currentResidentsResult = $conn->query($currentResidentsQuery);
$currentResidents = $currentResidentsResult->fetch_assoc()['total_residents'];

// Calculate remaining capacity
$remainingCapacity = $totalRoomCapacity - $currentResidents;

// Fetch upcoming check-ins
$upcomingCheckinsQuery = "SELECT COUNT(*) as total FROM Bookings WHERE check_in_date >= CURDATE()";
$upcomingCheckinsResult = $conn->query($upcomingCheckinsQuery);
$upcomingCheckins = $upcomingCheckinsResult->fetch_assoc()['total'];

// Fetch upcoming check-outs
$upcomingCheckoutsQuery = "SELECT COUNT(*) as total FROM Bookings WHERE check_out_date >= CURDATE()";
$upcomingCheckoutsResult = $conn->query($upcomingCheckoutsQuery);
$upcomingCheckouts = $upcomingCheckoutsResult->fetch_assoc()['total'];

// Fetch recent payments with resident name
$recentPaymentsQuery = "
SELECT r.name as resident_name, p.amount 
FROM Payments p
JOIN Bookings b ON p.booking_id = b.booking_id
JOIN Residents r ON b.resident_id = r.id
ORDER BY p.payment_date DESC LIMIT 5";
$recentPaymentsResult = $conn->query($recentPaymentsQuery);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matron Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <h1>Salvation Army Girls Hostel - Dashboard</h1>
        <div class="user-info">
            <p>Admin: [matron Name]</p>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <section class="main-metrics">
        <h2>Main Metrics</h2>
        <div class="metrics-grid">
            <div class="metric-box">
                <h3>Total Residents</h3>
                <p><?php echo $totalResidents; ?></p>
            </div>
            <div class="metric-box">
                <h3>Remaining Capacity</h3>
                <p><?php echo $remainingCapacity; ?></p>
            </div>
            <div class="metric-box">
                <h3>Upcoming Check-ins</h3>
                <p><?php echo $upcomingCheckins; ?></p>
            </div>
            <div class="metric-box">
                <h3>Upcoming Check-outs</h3>
                <p><?php echo $upcomingCheckouts; ?></p>
            </div>
        </div>

        <h2>Recent Payments</h2>
        <ul>
            <?php while($row = $recentPaymentsResult->fetch_assoc()): ?>
                <li><?php echo $row['resident_name'] . ' - $' . $row['amount']; ?></li>
            <?php endwhile; ?>
        </ul>

       <!-- Navigation Buttons -->
<div class="nav-buttons">
    <a href="residents.php" class="nav-button">View Residents</a>
    <a href="bookings.php" class="nav-button">Checkings/Check-outs</a>
    <a href="rooml.php" class="nav-button">View Rooms</a>
    <a href="payments.php" class="nav-button">View Payments</a>
    <a href="view_suppliers.php" class="nav-button">View Suppliers</a> 
    
</div>

<!-- New line for additional buttons -->
<div class="nav-buttons">
    <a href="view_order.php" class="nav-button">View Orders</a> 
    <a href="view_inventory.php" class="nav-button">View Inventory</a>
    <a href="view_calendar.php" class="nav-button">View Events</a>
    <a href="view_meal_plans.php" class="nav-button">View Meal Plans</a>
    
</div>



    </section>
</body>

</html>

<?php
$conn->close(); // Close the database connection
?>
