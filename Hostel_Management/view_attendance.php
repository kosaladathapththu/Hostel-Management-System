<?php
session_start();
include 'db_connect.php';

// Assuming the employee's session contains the employee_id
$employee_id = $_SESSION['employee']['employee_id'];

// Query to fetch check-in and check-out data for the current employee
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

// Create an associative array to store the data for each day.
$attendance_data = [];
while ($row = $result->fetch_assoc()) {
    $date = date('Y-m-d', strtotime($row['daily_in_time']));
    $attendance_data[$date][] = [
        'in_time' => $row['daily_in_time'],
        'out_time' => $row['daily_out_time'] ?? '0000-00-00 00:00:00', // Handle cases where there's no check-out time
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance Calendar</title>
    <link rel="stylesheet" href="view_attendance.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1>Employee Attendance Calendar</h1>

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
                    foreach ($attendance_data[$date] as $attendance) {
                        echo "<div class='attendance-time in'>In: " . date('H:i:s', strtotime($attendance['in_time'])) . "</div>";
                        if ($attendance['out_time'] != '0000-00-00 00:00:00') {
                            echo "<div class='attendance-time out'>Out: " . date('H:i:s', strtotime($attendance['out_time'])) . "</div>";
                        } else {
                            echo "<div class='attendance-time'>Out: Not yet checked out</div>";
                        }
                    }
                } else {
                    echo "<div class='attendance-time no-records'>No check-in/out records</div>";
                }

                echo "</div>";

                $day_counter++;
            }
            ?>
        </div>
        <a href="employee_atendance.php" class="view-button">print all employee records</a>
    </div>
</body>
</html>
