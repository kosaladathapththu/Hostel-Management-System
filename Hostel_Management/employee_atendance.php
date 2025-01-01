<?php
include 'db_connect.php';

// Fetch attendance records
$query = "
    SELECT 
        employee_daily_checkin.emp_attendance_id, 
        DATE(employee_daily_checkin.daily_in_time) AS attendance_date,
        TIME(employee_daily_checkin.daily_in_time) AS check_in_time,
        TIME(employee_daily_checkout.daily_out_time) AS check_out_time,
        SEC_TO_TIME(
            TIMESTAMPDIFF(SECOND, 
                employee_daily_checkin.daily_in_time, 
                employee_daily_checkout.daily_out_time)
        ) AS working_hours
    FROM 
        employee_daily_checkin
    LEFT JOIN 
        employee_daily_checkout 
    ON 
        employee_daily_checkin.emp_attendance_id = employee_daily_checkout.emp_attendance_id
    ORDER BY 
        attendance_date ASC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .no-data {
            text-align: center;
            font-weight: bold;
        }
        .print-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
        }
        .print-button:hover {
            background-color: #45a049;
        }
    </style>
    
</head>
<body>
<div class="header2">
            <img src="images/header.png" alt="Header Image">
        </div>
    <h1>Employee Attendance Record</h1>
    <a href="#" class="print-button" onclick="printPage()">Print Attendance</a>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Date</th>
                    <th>Check-In Time</th>
                    <th>Check-Out Time</th>
                    <th>Working Hours</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['emp_attendance_id']; ?></td>
                        <td><?php echo $row['attendance_date']; ?></td>
                        <td><?php echo $row['check_in_time']; ?></td>
                        <td><?php echo $row['check_out_time'] ? $row['check_out_time'] : 'N/A'; ?></td>
                        <td><?php echo $row['working_hours'] ? $row['working_hours'] : 'N/A'; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-data">No attendance records found.</p>
    <?php endif; ?>
<!-- Footer Section -->
<div class="footer">
        <img src="images/footer.png" alt="Footer Image">
    </div>
    </div>
    <script>
        function printPage() {
            // Hide the print button during printing
            const printButton = document.querySelector('.print-button');
            printButton.style.display = 'none';

            // Print the page
            window.print();

            // Show the print button after printing
            printButton.style.display = 'block';
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>
