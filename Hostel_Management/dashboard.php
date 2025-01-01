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

if ($matronResult === false) {
    // Query failed, handle error (e.g., log it or display a message)
    die("Error in fetching matron details: " . $stmt->error);
}

if ($matronResult->num_rows === 0) {
    header("Location: matron_auth.php"); // Redirect if matron not found
    exit();
}

// Assign matron's first name
$matronData = $matronResult->fetch_assoc();
$matron_first_name = $matronData['first_name'];

// Fetch total residents
$totalResidentsQuery = "SELECT COUNT(*) AS total FROM Residents";
$totalResidentsResult = $conn->query($totalResidentsQuery);
if ($totalResidentsResult === false) {
    die("Error in fetching total residents: " . $conn->error);
}
$totalResidents = $totalResidentsResult->fetch_assoc()['total'] ?? 0;

// Fetch total room capacity
$totalRoomCapacityQuery = "SELECT SUM(capacity) AS total_capacity FROM Rooms WHERE status = 'available'";
$totalRoomCapacityResult = $conn->query($totalRoomCapacityQuery);
if ($totalRoomCapacityResult === false) {
    die("Error in fetching total room capacity: " . $conn->error);
}
$totalRoomCapacity = $totalRoomCapacityResult->fetch_assoc()['total_capacity'] ?? 0;

// Fetch current residents in available rooms
$currentResidentsQuery = "SELECT COUNT(*) AS total_residents FROM Residents WHERE status = 'active'";
$currentResidentsResult = $conn->query($currentResidentsQuery);
if ($currentResidentsResult === false) {
    die("Error in fetching current residents: " . $conn->error);
}
$currentResidents = $currentResidentsResult->fetch_assoc()['total_residents'] ?? 0;

// Calculate remaining capacity
$remainingCapacity = $totalRoomCapacity - $currentResidents;

// Fetch upcoming check-ins
$upcomingCheckinsQuery = "SELECT COUNT(*) AS total FROM `resident_checking-checkouts` WHERE check_in_date >= CURDATE()";
$upcomingCheckinsResult = $conn->query($upcomingCheckinsQuery);
if ($upcomingCheckinsResult === false) {
    die("Error in fetching upcoming check-ins: " . $conn->error);
}
$upcomingCheckins = $upcomingCheckinsResult->fetch_assoc()['total'] ?? 0;

// Fetch upcoming check-outs
$upcomingCheckoutsQuery = "SELECT COUNT(*) AS total FROM `resident_checking-checkouts` WHERE check_out_date >= CURDATE()";
$upcomingCheckoutsResult = $conn->query($upcomingCheckoutsQuery);
if ($upcomingCheckoutsResult === false) {
    die("Error in fetching upcoming check-outs: " . $conn->error);
}
$upcomingCheckouts = $upcomingCheckoutsResult->fetch_assoc()['total'] ?? 0;

// Fetch recent payments with resident name
$recentPaymentsQuery = "
SELECT r.resident_name AS resident_name, t.amount 
FROM transactionss t
JOIN Residents r ON t.resident_id = r.id
ORDER BY t.trant_payment_date DESC LIMIT 5";
$recentPaymentsResult = $conn->query($recentPaymentsQuery);
if ($recentPaymentsResult === false) {
    die("Error in fetching recent payments: " . $conn->error);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salvation Army Girls Hostel -Matron Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    /* Snow animation styles */
    .snowflake {
        position: fixed;
        top: -10px;
        z-index: 9999;
        user-select: none;
        cursor: default;
        animation-name: snowfall;
        animation-duration: 10s;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
        opacity: 0.7;
    }

    @keyframes snowfall {
        0% {
            transform: translateY(0) rotate(0deg);
        }
        100% {
            transform: translateY(100vh) rotate(360deg);
        }
    }
</style>

</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="The_Salvation_Army.png" alt="Logo" class="logo">
                <h2>Salvation Army</h2>
                <p>Girls Hostel</p>

            </div>
            
            <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-item active" data-page="dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="residents.php" class="nav-item">
                <i class="fas fa-users"></i>
                <span>Residents</span>
            </a>
            <a href="rooml.php" class="nav-item">
                <i class="fas fa-bed"></i>
                <span>Rooms</span>
            </a>
            <a href="payments.php" class="nav-item">
                <i class="fas fa-money-bill-wave"></i>
                <span>Payments</span>
            </a>
            <a href="view_calendar.php" class="nav-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Events</span>
            </a>
            <a href="view_meal_plans.php" class="nav-item">
                <i class="fas fa-utensils"></i>
                <span>Meal Plans</span>
            </a>
            <a href="view_order.php" class="nav-item">
                <i class="fas fa-shopping-cart"></i>
                <span>Orders</span>
            </a>
            <a href="view_inventory.php" class="nav-item">
                <i class="fas fa-clipboard-list"></i>
                <span>Inventory</span>
            </a>
            <a href="bookings.php" class="nav-item">
                <i class="fas fa-clipboard-check"></i>
                <span>Checking/Checkouts</span>
            </a>
            <a href="view_suppliers.php" class="nav-item">
                <i class="fas fa-truck"></i>
                <span>Suppliers</span>
            </a>
            <a href="edit_matron_profile.php" class="nav-item">
            <i class="fas fa-user-edit"></i>
            <span>Edit Profile</span>
             </a>

        </nav>
    </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="top-header">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
                <div class="header-right">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="admin-profile">
                        <img src="matron.png" alt="Admin" class="avatar">
                        <div class="admin-info">
                        <span class="admin-name"><?php echo htmlspecialchars($matron_first_name); ?></span>
                            <p class="admin-role">Matron</p>
                        </div>
                    </div>
                    <a href="matron_logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>

                    <a href="edit_matron_profile.php" class="profile-btn">
                    <i class="fas fa-user-edit"></i> Edit Profile
                    </a>

                </div>
            </header>
   
            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Metrics Grid -->
                <div class="metrics-grid">
                    <div class="metric-card">
                        <div class="metric-info">
                            <h3>Total Residents</h3>
                            <p class="metric-value"><?php echo $totalResidents; ?></p>
                        </div>
                        <div class="metric-icon residents">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-info">
                            <h3>Remaining Capacity</h3>
                            <p class="metric-value"><?php echo $remainingCapacity; ?></p>
                        </div>
                        <div class="metric-icon capacity">
                            <i class="fas fa-bed"></i>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-info">
                            <h3>Upcoming Check-ins</h3>
                            <p class="metric-value"><?php echo $upcomingCheckins; ?></p>
                        </div>
                        <div class="metric-icon check-ins">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-info">
                            <h3>Upcoming Check-outs</h3>
                            <p class="metric-value"><?php echo $upcomingCheckouts; ?></p>
                        </div>
                        <div class="metric-icon check-outs">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="charts-section">
                    <div class="chart-card">
                        <h3>Occupancy Trend</h3>
                        <canvas id="occupancyChart"></canvas>
                    </div>
                    <div class="chart-card">
                        <h3>Recent Payments</h3>
                        <div class="payments-list">
                            <?php while($row = $recentPaymentsResult->fetch_assoc()): ?>
                            <div class="payment-item">
                                <div class="payment-info">
                                    <i class="fas fa-money-bill"></i>
                                    <span class="resident-name"><?php echo $row['resident_name']; ?></span>
                                </div>
                                <span class="payment-amount">Rs.<?php echo $row['amount']; ?></span>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="dashboard.js"></script>
    <script>
    // Christmas Snow Animation Script
    function createSnowflake() {
        // Check if current month is December
        const currentDate = new Date();
        if (currentDate.getMonth() !== 11) { // 11 represents December (0-indexed)
            return; // Do not create snowflakes if not December
        }

        const snowflake = document.createElement('div');
        snowflake.classList.add('snowflake');
        snowflake.innerHTML = '❄️';

        // Randomize snowflake properties
        const size = Math.random() * 10 + 5; // 5-15px
        snowflake.style.fontSize = `${size}px`;
        snowflake.style.left = `${Math.random() * 100}%`;
        snowflake.style.animationDuration = `${Math.random() * 10 + 5}s`; // 5-15s
        snowflake.style.opacity = Math.random();
        
        document.body.appendChild(snowflake);

        // Remove snowflake after animation
        setTimeout(() => {
            snowflake.remove();
        }, 15000);
    }

    // Create snowflakes periodically only in December
    function startSnowfall() {
        const currentDate = new Date();
        if (currentDate.getMonth() === 11) { // Check if it's December
            setInterval(createSnowflake, 300); // Create a snowflake every 300ms
        }
    }

    // Start snowfall when page loads
    window.addEventListener('load', startSnowfall);
</script>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
