<?php
include 'db_connect.php'; // Include database connection

session_start(); // Start session

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Redirect if not logged in
if (!isset($_SESSION['resident_id'])) {
    header("Location: login.php");
    exit();
}

// Regenerate session ID for security
session_regenerate_id(true);
// Fetch resident_id from session
$resident_id = $_SESSION['resident_id'];

// Fetch resident data
$residentQuery = "SELECT username FROM residents WHERE id = ?";
$residentStmt = $conn->prepare($residentQuery);
$residentStmt->bind_param("i", $resident_id);
$residentStmt->execute();
$residentResult = $residentStmt->get_result();
$residentData = $residentResult->fetch_assoc();

// Fetch session data

$resident_id = $_SESSION['resident_id'];
$resident_name = $_SESSION['resident_name'];


// Fetch profile picture
$profileQuery = "SELECT profile_picture FROM residents WHERE id = ?";
$profileStmt = $conn->prepare($profileQuery);

if ($profileStmt === false) {
    die("Prepare failed: " . $conn->error);
}

$profileStmt->bind_param("i", $resident_id);
$profileStmt->execute();
$profileResult = $profileStmt->get_result();
$profileData = $profileResult->fetch_assoc() ?? ['profile_picture' => null];
$profileStmt->close();

// Fetch room info
$roomInfoQuery = "
    SELECT r.room_number, rs.status 
    FROM rooms r 
    JOIN residents rs ON r.room_number = rs.resident_room_no 
    WHERE rs.id = ?";

$roomStmt = $conn->prepare($roomInfoQuery);

if ($roomStmt === false) {
    die("Prepare failed: " . $conn->error);
}

$roomStmt->bind_param("i", $resident_id);
$roomStmt->execute();
$roomInfoResult = $roomStmt->get_result();
$roomInfo = $roomInfoResult->fetch_assoc() ?? ['resident_room_no' => 'N/A', 'status' => 'Unknown'];
$roomStmt->close();

// Fetch upcoming events
$upcomingEventsQuery = "SELECT title, start_date FROM events WHERE start_date >= CURDATE() ORDER BY start_date ASC LIMIT 5";
$upcomingEventsResult = $conn->query($upcomingEventsQuery);

if ($upcomingEventsResult === false) {
    die("Query failed: " . $conn->error);
}

// Fetch resident's upcoming check-in and check-out dates
$checkinCheckoutQuery = "
    SELECT check_in_date, check_out_date 
    FROM `resident_checking-checkouts` 
    WHERE resident_id = ? AND status = 'active'";
$checkinStmt = $conn->prepare($checkinCheckoutQuery);
if ($checkinStmt === false) {
    die('MySQL prepare error: ' . $conn->error);  // Check for errors
}
$checkinStmt->bind_param("i", $resident_id);
$checkinStmt->execute();
$checkinCheckoutResult = $checkinStmt->get_result();
$checkinCheckout = $checkinCheckoutResult->fetch_assoc();
$checkinStmt->close();


// Fetch past check-in/check-out records for checking checkouts
$pastCheckinsQuery = "
    SELECT check_in_date, check_out_date 
    FROM `resident_checking-checkouts` 
    WHERE resident_id = ? AND status = 'approved' 
    ORDER BY check_in_date DESC";
$pastCheckinsStmt = $conn->prepare($pastCheckinsQuery);
if ($pastCheckinsStmt === false) {
    die('MySQL prepare error: ' . $conn->error);  // Check for errors
}
$pastCheckinsStmt->bind_param("i", $resident_id);
$pastCheckinsStmt->execute();
$pastCheckinsResult = $pastCheckinsStmt->get_result();
$pastCheckinsStmt->close();


// Check if check-out date has passed
$showRequestCheckings = true;
if (isset($checkinCheckout['check_out_date']) && strtotime($checkinCheckout['check_out_date']) < time()) {
    $showRequestCheckings = false;
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Dashboard</title>
    <link rel="stylesheet" href="resident_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

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
            </div>
            <nav class="sidebar-nav">
                <ul>
                
                    <li class="active">
                        <a href="resident_dashboard.php"><i class="fas fa-home"></i>Dashboard</a>
                    </li>
                    <li>
                        <a href="edit_profile.php"><i class="fas fa-user"></i>Profile</a>
                    </li>
                    <li>
                        <a href="resident_view_meal_plans.php"><i class="fas fa-utensils"></i>Meals</a>
                    </li>
                    <li>
                        <a href="update_checkin_checkout.php"><i class="fas fa-calendar-check"></i>Check-in/out</a>
                    </li>
                    <li>
                        <a href="Re_view_calendar.php"><i class="fas fa-calendar"></i>Events</a>
                    </li>
                    <li>
                        <a href="transaction.php"><i class="fa fa-credit-card"></i>Monthly Fee</a>
                    </li>
                    <li>
                        <a href="#support"><i class="fas fa-headset"></i>Support</a>
                    </li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <a href="?logout=true" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Navigation -->
            <nav class="top-nav">
    <div class="nav-left">
        <button id="sidebar-toggle" class="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search...">
            <div class="search-results">
                <div class="search-results-content">
                    <!-- Search results will be populated here -->
                </div>
            </div>
        </div>
    </div>
    
    <div class="user-menu">
        <!-- Messages Dropdown -->
        <div class="menu-item messages-dropdown">
            <button class="icon-button">
                <i class="fas fa-envelope"></i>
                <span class="badge">4</span>
            </button>
            <div class="dropdown-content messages-content">
                <div class="dropdown-header">
                    <h3>Messages</h3>
                    <a href="#" class="view-all">View All</a>
                </div>
                <div class="dropdown-body">
                    <a href="#" class="message-item unread">
                        <img src="assets/default_profile.png" alt="Sender" class="sender-avatar">
                        <div class="message-content">
                            <div class="message-info">
                                <h4>Room Service</h4>
                                <span class="time">2m ago</span>
                            </div>
                            
                            <p>Your room cleaning is scheduled...</p>
                        </div>
                    </a>
                    <!-- Add more message items as needed -->
                </div>
            </div>
        </div>

        <!-- Notifications Dropdown -->
        <div class="menu-item notifications-dropdown">
            <button class="icon-button">
                <i class="fas fa-bell"></i>
                <span class="badge"><?php echo $notificationCount ?? '3'; ?></span>
            </button>
            <div class="dropdown-content notifications-content">
                <div class="dropdown-header">
                    <h3>Notifications</h3>
                    <a href="#" class="view-all">View All</a>
                </div>
                <div class="dropdown-body">
                    <a href="#" class="notification-item unread">
                        <div class="notification-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="notification-content">
                            <p>New event scheduled for tomorrow</p>
                            <span class="time">1h ago</span>
                        </div>
                    </a>
                    <!-- Add more notification items as needed -->
                </div>
            </div>
        </div>

       <!-- Profile Dropdown -->
<div class="menu-item profile-dropdown">
    <button class="profile-button">
        <img src="<?php echo !empty($profileData['profile_picture']) ? 'uploads/' . htmlspecialchars($profileData['profile_picture']) : 'assets/default_profile.png'; ?>" 
             alt="Profile" 
             class="profile-picture"
             onerror="this.src='assets/default_profile.png'">
        <span class="profile-name"><?php echo htmlspecialchars($residentData['username']); ?></span>
        <i class="fas fa-chevron-down"></i>
    </button>
    <div class="dropdown-content profile-content">
        <div class="dropdown-header profile-header">
            <img src="<?php echo !empty($profileData['profile_picture']) ? 'uploads/' . $profileData['profile_picture'] : 'assets/default_profile.png'; ?>" 
                 alt="Profile"
                 class="large-profile-picture"
                 onerror="this.src='assets/default_profile.png'">
            <div class="profile-info">
                <h3><?php echo htmlspecialchars($resident_name); ?></h3>
                <p>Room <?php echo $roomInfo['room_number'] ?? 'Not Assigned'; ?></p>
            </div>
        </div>
        <div class="dropdown-body">
            <a href="edit_profile.php" class="dropdown-item">
                <i class="fas fa-user"></i> My Profile
            </a>
            <a href="#" class="dropdown-item">
                <i class="fas fa-cog"></i> Settings
            </a>
            <a href="#" class="dropdown-item">
                <i class="fas fa-question-circle"></i> Help Center
            </a>
            <div class="dropdown-divider"></div>
            <a href="?logout=true" class="dropdown-item logout-item">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</div>

</nav>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Welcome Section -->
                <div class="welcome-section">
                    <h1>Welcome back, <?php echo htmlspecialchars($residentData['username']); ?></h1>
                    <p>Here's what's happening today</p>
                </div>

                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon room-icon">
                            <i class="fas fa-bed"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Room Number</h3>
                            <p><?php echo $roomInfo['room_number'] ?? 'Not Assigned'; ?></p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon status-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Status</h3>
                            <p><?php echo $roomInfo['status'] ?? 'Unknown'; ?></p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon calendar-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Next Check-out</h3>
                            <p><?php echo isset($checkinCheckout['check_out_date']) ? date('M d, Y', strtotime($checkinCheckout['check_out_date'])) : 'N/A'; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Events and Activities -->
                <div class="content-grid">
                    <div class="content-card upcoming-events">
                        <div class="card-header">
                            <h2>Upcoming Events</h2>
                            <button class="view-all">View All</button>
                        </div>
                        <ul class="events-list">
                            <?php while($row = $upcomingEventsResult->fetch_assoc()): ?>
                            <li class="event-item">
                                <div class="event-date">
                                    <?php 
                                        $date = new DateTime($row['start_date']);
                                        echo $date->format('M');
                                        echo "<span>" . $date->format('d') . "</span>";
                                    ?>
                                </div>
                                <div class="event-details">
                                    <h4><?php echo $row['title']; ?></h4>
                                    <p><?php echo $date->format('g:i A'); ?></p>
                                </div>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>

                    <div class="content-card check-history">
                        <div class="card-header">
                            <h2>Check-in/out History</h2>
                            <button class="view-all">View All</button>
                        </div>
                        <div class="history-list">
                            <?php while($past = $pastCheckinsResult->fetch_assoc()): ?>
                            <div class="history-item">
                                <div class="history-icon">
                                    <i class="fas fa-history"></i>
                                </div>
                                <div class="history-details">
                                    <h4>Check Period</h4>
                                    <p>From: <?php echo date('M d, Y', strtotime($past['check_in_date'])); ?></p>
                                    <p>To: <?php echo date('M d, Y', strtotime($past['check_out_date'])); ?></p>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

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
