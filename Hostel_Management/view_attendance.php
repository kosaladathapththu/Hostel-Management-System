<?php
session_start();
include 'db_connect.php';

$employee = $_SESSION['employee'];
$employee_id = $_SESSION['employee']['employee_id'];

// Query to fetch the most recent check-in and check-out data for each day
$query = "
    SELECT 
        edc.emp_attendance_id, 
        DATE(edc.daily_in_time) as attendance_date,
        MIN(edc.daily_in_time) as first_in_time,
        MAX(edco.daily_out_time) as last_out_time 
    FROM 
        employee_daily_checkin edc
    LEFT JOIN 
        employee_daily_checkout edco ON edc.emp_attendance_id = edco.emp_attendance_id
    JOIN 
        employee_attendance ea ON edc.emp_attendance_id = ea.emp_attendance_id
    WHERE 
        ea.employee_id = ?
    GROUP BY 
        attendance_date
    ORDER BY 
        attendance_date DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

// Create an associative array to store the data for each day
$attendance_data = [];
while ($row = $result->fetch_assoc()) {
    $attendance_data[$row['attendance_date']] = [
        'in_time' => $row['first_in_time'],
        'out_time' => $row['last_out_time'] ?? '0000-00-00 00:00:00',
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance Calendar</title>
    <link rel="stylesheet" href="view_attendance.css">
    <link rel="stylesheet" href="employee_view_leave_status.css"> 
    <link rel="stylesheet" href="employee_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
            <!-- Sidebar -->
            <div class="sidebar">
            <div class="logo-container">
        <img src="The_Salvation_Army.png" alt="Logo" class="logo" style="width: 60px; height: 60px;"> 

        <h2>Salvation Army<br>Girls Hostel</h2>
    </div>
        <ul class="nav-menu">
            <li><a href="employee_dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="employee_view_leave_status.php"><i class="fas fa-calendar-check"></i><span>Leave Status</span></a></li>
            <li><a href="employee_view_remaining_leave.php"><i class="fas fa-calendar-alt"></i><span>Remaining Leave</span></a></li>
            <li><a href="checkin.php"><i class="fas fa-sign-in-alt"></i><span>Check-in</span></a></li>
            <li><a href="update_ckeckout.php"><i class="fas fa-sign-out-alt"></i><span>Check-out</span></a></li>
            <li><a href="view_attendance.php"><i class="fas fa-clipboard-list"></i><span>Attendance</span></a></li>

            <li><a href="view_paysheet.php"><i class="fas fa-file-invoice-dollar"></i><span>Paysheet</span></a></li>
            <li><a href="employee_change_details.php"><i class="fas fa-user-edit"></i><span>Profile</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
    <div class="header" style="width: 100%;">
            <h1>Welcome, <?php echo $employee['name']; ?></h1>
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <a href="employee_logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    <h2>Your Attendance Record</h2>
    <div class="breadcrumbs">
    <a href="checkin.php" class="breadcrumb-item">
        <i class="fas fa-sign-in-alt"></i> Checkin
    </a>
    <span class="breadcrumb-separator">|</span>
    <a href="update_ckeckout.php" class="breadcrumb-item">
        <i class="fas fa-sign-out-alt"></i> CheckOut
    </a>
    <span class="breadcrumb-separator">|</span>
    <a href="employee_dashboard.php" class="breadcrumb-item">
        <i class="fas fa-home"></i> Back to Dashboard
    </a>
</div>

    <div class="calendar-container">
        <div class="calendar-header">
            <div class="calendar-header-day">Sunday</div>
            <div class="calendar-header-day">Monday</div>
            <div class="calendar-header-day">Tuesday</div>
            <div class="calendar-header-day">Wednesday</div>
            <div class="calendar-header-day">Thursday</div>
            <div class="calendar-header-day">Friday</div>
            <div class="calendar-header-day">Saturday</div>
        </div>

        <div class="calendar-body">
            <?php
            // Example: Displaying calendar for December 2024 (you can dynamically generate for the current month)
            $month = '2024-12';
            $days_in_month = cal_days_in_month(CAL_GREGORIAN, 12, 2024);
            $first_day_of_month = date('w', strtotime($month . '-01')); // Get the first day of the month (0 = Sunday, 1 = Monday, etc.)
            
            $day_counter = 1;

            // Loop through the first empty spaces (before the first day of the month)
            for ($i = 0; $i < $first_day_of_month; $i++) {
                echo "<div class='calendar-day empty'></div>";
            }

            // Loop through all days of the month
            for ($day = 1; $day <= $days_in_month; $day++) {
                $date = $month . '-' . str_pad($day, 2, '0', STR_PAD_LEFT); // Format date as 'Y-m-d'
                echo "<div class='calendar-day'>";
                echo "<strong>" . $day . "</strong>";

                // Check if attendance data is available for this date
                if (isset($attendance_data[$date])) {
                    $attendance = $attendance_data[$date];
                    echo "<div class='attendance-time in'>In: " . date('H:i:s', strtotime($attendance['in_time'])) . "</div>";
                    if ($attendance['out_time'] != '0000-00-00 00:00:00') {
                        echo "<div class='attendance-time out'>Out: " . date('H:i:s', strtotime($attendance['out_time'])) . "</div>";
                    } else {
                        echo "<div class='attendance-time out'>Out: Not yet checked out</div>";
                    }
                } else {
                    echo "<div class='attendance-time no-records'>No check-in/out records</div>";
                }

                echo "</div>";

                $day_counter++;
            }
            ?>
        </div>
    </div>
</body>
</html>