<?php
include 'db_connect.php'; // Include database connection

session_start(); // Start session

// Check if resident is logged in; if not, redirect to login page
if (!isset($_SESSION['resident_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch resident_id from session
$resident_id = $_SESSION['resident_id'];

// Fetch resident data
$residentQuery = "SELECT username FROM residents WHERE id = ?";
$residentStmt = $conn->prepare($residentQuery);
$residentStmt->bind_param("i", $resident_id);
$residentStmt->execute();
$residentResult = $residentStmt->get_result();
$residentData = $residentResult->fetch_assoc();


// Fetch events from the database
$eventsQuery = "SELECT * FROM Events";
$eventsResult = $conn->query($eventsQuery);

$eventsArray = [];
while ($row = $eventsResult->fetch_assoc()) {
    $eventsArray[] = [
        'id' => $row['event_id'],
        'title' => $row['title'],
        'start' => $row['start_date'],
        'end' => $row['end_date'],
        'description' => $row['description']
    ];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Calendar</title>
    <link rel="stylesheet" href="view_calendar.css">
    <!-- Include FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <link rel="stylesheet" href="view_meal_plans.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>



<body>
        <!-- Sidebar -->
        <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Resident Panel</h2>
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
                <button onclick="window.location.href='edit_profile.php'" class="edit-btn"><i class="fas fa-user-edit"></i> Edit Profile</button>
                <button onclick="window.location.href='login.php'" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div class="header-left">
                <img src="The_Salvation_Army.png" alt="Logo" class="logo">
            </div>
             <center><b><h2 style="text-align:left; color:black;">Salvation Army Girls Hostel</h2></b></center>
             <h4 style="text-align:left;color:black">Welcome,<?php echo htmlspecialchars($residentData['username']); ?></h4>
        </header>

        <section class="meal-plans-list">
            <h2 style="margin-top:10px; margin-left:10px;">Upcoming Events</h2>
            <div class="breadcrumbs">

    <span class="breadcrumb-separator">|</span>
    <a href="resident_dashboard.php" class="breadcrumb-item">
        <i class="fas fa-home"></i> Resident Dashboard
    </a>

</div>

   

    <div id="calendar"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?php echo json_encode($eventsArray); ?>, 
                editable: true,
                selectable: true,
            })

            calendar.render();
        });
    </script>
</body>
</html>
