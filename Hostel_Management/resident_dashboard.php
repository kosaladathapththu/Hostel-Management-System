<?php
include 'db_connect.php'; // Include database connection

session_start(); // Start session

// Handle logout if the logout button is clicked
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Check if resident is logged in; if not, redirect to login page
if (!isset($_SESSION['resident_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch resident_id and name from session
$resident_id = $_SESSION['resident_id']; 
$resident_name = $_SESSION['resident_name'];

// Fetch resident's room information directly from the residents table
$roomInfoQuery = "
    SELECT r.room_number, rs.status 
    FROM rooms r 
    JOIN residents rs ON r.room_id = rs.room_id 
    WHERE rs.id = ?";
$roomStmt = $conn->prepare($roomInfoQuery);
$roomStmt->bind_param("i", $resident_id);
$roomStmt->execute();
$roomInfoResult = $roomStmt->get_result();
$roomInfo = $roomInfoResult->fetch_assoc();

// Fetch upcoming events for residents
$upcomingEventsQuery = "SELECT title, start_date FROM events WHERE start_date >= CURDATE() ORDER BY start_date ASC LIMIT 5";
$upcomingEventsResult = $conn->query($upcomingEventsQuery);

// Fetch resident's upcoming check-in and check-out dates
$checkinCheckoutQuery = "
    SELECT check_in_date, check_out_date 
    FROM bookings 
    WHERE resident_id = ? AND status = 'active'";
$checkinStmt = $conn->prepare($checkinCheckoutQuery);
$checkinStmt->bind_param("i", $resident_id);
$checkinStmt->execute();
$checkinCheckoutResult = $checkinStmt->get_result();
$checkinCheckout = $checkinCheckoutResult->fetch_assoc();

// Fetch past check-in/check-out records for checking checkouts
$pastCheckinsQuery = "
    SELECT check_in_date, check_out_date 
    FROM bookings 
    WHERE resident_id = ? AND status = 'approved' 
    ORDER BY check_in_date DESC";
$pastCheckinsStmt = $conn->prepare($pastCheckinsQuery);
$pastCheckinsStmt->bind_param("i", $resident_id);
$pastCheckinsStmt->execute();
$pastCheckinsResult = $pastCheckinsStmt->get_result();

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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Salvation Army-Resident Dashboard</h1>
        <div class="profile-info">
            <?php
            // Fetch the profile picture path from the database
            $residentQuery = "SELECT profile_picture FROM residents WHERE id = ?";
            $residentStmt = $conn->prepare($residentQuery);
            $residentStmt->bind_param("i", $resident_id);
            $residentStmt->execute();
            $residentResult = $residentStmt->get_result();
            $residentData = $residentResult->fetch_assoc();

            // Display profile picture or default icon
            if (!empty($residentData['profile_picture'])):
            ?>
                <img src="<?php echo $residentData['profile_picture']; ?>" alt="Profile Picture" class="profile-picture">
            <?php else: ?>
                <img src="default_profile.png" alt="Default Profile" class="profile-picture"> <!-- Placeholder for default image -->
            <?php endif; ?>
            <a href="edit_profile.php" class="edit-profile-btn">Edit Profile</a>
        </div>

        <div class="user-info">
            <p>Welcome, <?php echo $resident_name; ?></p> <!-- Resident's name displayed -->
            <a href="?logout=true" class="logout-btn">Logout</a>
        </div>
    </header>

    <section class="main-metrics">
        <h2>Room Details</h2>
        <div class="metrics-grid">
            <div class="metric-box">
                <h3>Room Number</h3>
                <p><?php echo $roomInfo['room_number'] ?? 'Not Assigned'; ?></p>
            </div>
            <div class="metric-box">
                <h3>Status</h3>
                <p><?php echo $roomInfo['status'] ?? 'Unknown'; ?></p>
            </div>
        </div>

        <h2>Upcoming Events</h2>
        <ul>
            <?php while($row = $upcomingEventsResult->fetch_assoc()): ?>
                <li><?php echo $row['title'] . ' - ' . $row['start_date']; ?></li>
            <?php endwhile; ?>
        </ul>

        <h2>Checking Checkouts</h2>
        <div class="past-checkouts">
            <?php if ($pastCheckinsResult->num_rows > 0): ?>
                <ul>
                    <?php while($past = $pastCheckinsResult->fetch_assoc()): ?>
                        <li>Check-in: <?php echo date('Y-m-d', strtotime($past['check_in_date'])); ?> | 
                            Check-out: <?php echo date('Y-m-d', strtotime($past['check_out_date'])); ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No past check-in/check-out records found.</p>
            <?php endif; ?>
        </div>

        <?php if ($showRequestCheckings): ?>
            <a href="update_checkin_checkout.php" class="feedback-btn">Request Checkings</a>
        <?php endif; ?>

        <a href="resident_view_meal_plans.php" class="feedback-btn">Meals Feedback</a>
    </section>

            <script>
        // Confirm Logout Script
        document.querySelector('.logout-btn').addEventListener('click', function(e) {
            if (!confirm("Are you sure you want to log out?")) {
                e.preventDefault(); // Prevent logout if not confirmed
            }
        });

        // Tooltips for Status Information
        const statusTooltip = document.createElement('div');
        statusTooltip.className = 'tooltiptext';
        statusTooltip.innerText = 'Shows current room status'; 
        document.querySelector('.metric-box h3').appendChild(statusTooltip);
        </script>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
