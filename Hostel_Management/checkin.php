<?php
session_start();
if (!isset($_SESSION['employee'])) {
    header("Location: employee_login.php");
    exit();
}
include 'db_connect.php';

$employee = $_SESSION['employee'];
$employee_id = $employee['employee_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $time_block = $_POST['time_block'];

    // Check if the employee already has an attendance record for today
    $query = "SELECT emp_attendance_id FROM employee_attendance WHERE employee_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Get the existing attendance ID
        $row = $result->fetch_assoc();
        $emp_attendance_id = $row['emp_attendance_id'];
    } else {
        // Create a new attendance record in employee_attendance
        $insertQuery = "INSERT INTO employee_attendance (employee_id) VALUES (?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        $emp_attendance_id = $stmt->insert_id;
    }

    // Now, insert a new check-in record each time the user submits
    $insertCheckinQuery = "INSERT INTO employee_daily_checkin (emp_attendance_id, daily_in_time) VALUES (?, ?)";
    $stmt = $conn->prepare($insertCheckinQuery);
    $stmt->bind_param("is", $emp_attendance_id, $time_block);

    if ($stmt->execute()) {
        echo "<script>alert('Check-in successful.');</script>";
        header("Location: employee_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Error during check-in. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-In | Salvation Army Girls Hostel</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="checkin.css">
</head>
<body>
    <div class="main-container">
        <div class="main-content">
            <h1>Check-In</h1>
            <p><strong>Date: <?php echo date('Y-m-d'); ?></strong></p> <!-- Display current date -->
            <p>Welcome, <?php echo $employee['name']; ?>. Please select a time block to check in.</p>

            <form method="POST" action="">
                <label for="time_block">Select Time:</label>
                <input type="datetime-local" id="time_block" name="time_block" required>
                <br><br>
                <button type="submit" class="btn">Confirm Check-In</button>
            </form>
        </div>


    </div>
</body>
</html>
