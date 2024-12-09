<?php
session_start();
include 'db_connect.php';

// Fetch admin name
$admin_name = "Admin";
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $adminQuery = "SELECT admin_name FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($adminQuery);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $admin_name = $row['admin_name'];
    }
    $stmt->close();
}

// Ensure employee ID is passed via URL
if (!isset($_GET['id'])) {
    die("Error: Employee ID not provided.");
}

$employee_id = $_GET['id'];

// Improved query to fetch attendance data with consolidated check-in and check-out times
$query = "
    SELECT 
        DATE(edc.daily_in_time) as attendance_date,
        MIN(edc.daily_in_time) as first_in_time,
        MAX(COALESCE(edco.daily_out_time, '0000-00-00 00:00:00')) as last_out_time 
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

// Prepare consolidated attendance data for the calendar
$attendance_data = [];
while ($row = $result->fetch_assoc()) {
    $attendance_data[$row['attendance_date']] = [
        'in_time' => $row['first_in_time'],
        'out_time' => $row['last_out_time'] === '0000-00-00 00:00:00' ? 'Not Checked Out' : $row['last_out_time']
    ];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance Calendar</title>
    <link rel="stylesheet" href="view_attendance.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Admin Panel</h2>
        <div class="profile-info">
            <p><i class="fas fa-user"></i> <?php echo htmlspecialchars($admin_name); ?></p>
        </div>
        <ul>
            <li><a href="view_employee.php"><i class="fas fa-users"></i> Employee Management</a></li>
            <li><a href="view_employee_vacancies.php"><i class="fas fa-briefcase"></i> Employee Vacancies</a></li>
            <li><a href="view_applications.php"><i class="fas fa-file-alt"></i> Job Applications</a></li>
            <li><a href="admin_approve_matron.php"><i class="fas fa-user-check"></i> Matron Applications</a></li>
            <li><a href="view_attendance_by_admin.php"><i class="fas fa-calendar-check"></i> Attendance Record</a></li>
            <li><a href="view_leave_requests.php"><i class="fas fa-envelope"></i> Leave Requests</a></li>
            <li><a href="view_payroll.php"><i class="fas fa-money-check-alt"></i> Payroll System</a></li>
            <li><a href="generate_payroll_reports.php"><i class="fas fa-chart-line"></i> Payroll Report</a></li>
            <li><a href="view_social_service.php"><i class="fas fa-server"></i> View Social Services</a></li>
            <li><a href="add_social_services.php"><i class="fas fa-bars"></i> Add Social Services</a></li>
        </ul>
        <button onclick="window.location.href='admin_edit_profile.php'" class="edit-btn"><i class="fas fa-user-edit"></i> Edit Profile</button>
        <button onclick="window.location.href='admin_logout.php'" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div class="header-left">
                <img src="The_Salvation_Army.png" alt="Logo" class="logo">
            </div>
            <center><h1>Salvation Army Girls Hostel</h1></center>
            <div class="header-right">
                <p>Welcome, <?php echo htmlspecialchars($admin_name); ?></p>
            </div>
        </header>

        <!-- Breadcrumbs -->
        <div class="breadcrumbs">
            <a href="admin_dashboard.php" class="breadcrumb-item">
                <i class="fas fa-home"></i> Admin Dashboard
            </a>
            <span class="breadcrumb-separator">|</span>
            <a href="view_attendance_by_admin.php" class="breadcrumb-item">
                <i class="fas fa-calendar"></i> Attendance
            </a>
        </div>

        <h3><i>Attendance Calendar for Employee ID: <?php echo htmlspecialchars($employee_id); ?></i></h3>

        <!-- Calendar -->
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
                $month = date('Y-m');
                $days_in_month = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
                $first_day_of_month = date('w', strtotime($month . '-01'));

                for ($i = 0; $i < $first_day_of_month; $i++) {
                    echo "<div class='calendar-day empty'></div>";
                }

                for ($day = 1; $day <= $days_in_month; $day++) {
                    $date = $month . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                    echo "<div class='calendar-day'>";
                    echo "<strong>" . $day . "</strong>";

                    if (isset($attendance_data[$date])) {
                        $attendance = $attendance_data[$date];
                        echo "<div class='attendance-time in'>In: " . date('H:i:s', strtotime($attendance['in_time'])) . "</div>";
                        echo "<div class='attendance-time out'>Out: " . ($attendance['out_time'] !== 'Not Checked Out' ? date('H:i:s', strtotime($attendance['out_time'])) : $attendance['out_time']) . "</div>";
                    } else {
                        echo "<div class='attendance-time no-records'>No records</div>";
                    }

                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>