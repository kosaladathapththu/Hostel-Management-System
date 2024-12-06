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

// Query to fetch attendance data
$query = "
    SELECT 
        edc.emp_attendance_id, 
        edc.daily_in_time, 
        edco.daily_out_time 
    FROM 
        employee_daily_checkin edc
    LEFT JOIN 
        employee_daily_checkout edco ON edc.emp_attendance_id = edco.emp_attendance_id
    JOIN 
        employee_attendance ea ON edc.emp_attendance_id = ea.emp_attendance_id
    WHERE 
        ea.employee_id = ?
    ORDER BY 
        edc.daily_in_time DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

// Prepare attendance data for the calendar
$attendance_data = [];
while ($row = $result->fetch_assoc()) {
    $date = date('Y-m-d', strtotime($row['daily_in_time']));
    $attendance_data[$date][] = [
        'in_time' => $row['daily_in_time'],
        'out_time' => $row['daily_out_time'] ?? 'Not Checked Out',
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
                        foreach ($attendance_data[$date] as $attendance) {
                            echo "<div class='attendance-time in'>In: " . date('H:i:s', strtotime($attendance['in_time'])) . "</div>";
                            echo "<div class='attendance-time out'>Out: " . ($attendance['out_time'] !== 'Not Checked Out' ? date('H:i:s', strtotime($attendance['out_time'])) : $attendance['out_time']) . "</div>";
                        }
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
