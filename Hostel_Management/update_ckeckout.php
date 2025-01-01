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
        // If no attendance record, create a new attendance record in employee_attendance
        $insertQuery = "INSERT INTO employee_attendance (employee_id) VALUES (?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        $emp_attendance_id = $stmt->insert_id;
    }

    // Now, insert a new check-out record when the user submits
    $insertCheckoutQuery = "INSERT INTO employee_daily_checkout (emp_attendance_id, daily_out_time) VALUES (?, ?)";
    $stmt = $conn->prepare($insertCheckoutQuery);
    $stmt->bind_param("is", $emp_attendance_id, $time_block);

    if ($stmt->execute()) {
        echo "<script>alert('Check-out successful.');</script>";
        header("Location: employee_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Error during check-out. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-Out | Salvation Army Girls Hostel</title>
    <link rel="stylesheet" href="employee_view_leave_status.css"> 
    <link rel="stylesheet" href="employee_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="update_checkout.css">
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
    <h2>Confirm Your Check-Out</h2>
    <div class="breadcrumbs">

    <span class="breadcrumb-separator">|</span>
    <a href="employee_dashboard.php" class="breadcrumb-item">
        <i class="fas fa-home"></i> Back to Dashboard
    </a>
</div>
<div class="main-container1" style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f5f7fa; padding: 20px;">
    <div class="main-content1" style="width: 50%; max-width: 600px; padding: 20px; background-color: #ffffff; border: 2px solid #4e54c8; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); text-align: center;">
        <h1 style="color: #4e54c8; margin-bottom: 20px;">Check-Out</h1>
        <p style="color: #2c3e50; font-size: 16px; margin-bottom: 10px;"><strong>Date: <?php echo date('Y-m-d'); ?></strong></p>
        <p style="color: #2c3e50; font-size: 14px; margin-bottom: 20px;"> Please select a time block to check out.</p>

        <form method="POST" action="" style="margin-bottom: 20px;">
            <label for="time_block" style="display: block; margin-bottom: 10px; color: #4e54c8; font-weight: bold;">Select Time:</label>
            <input type="datetime-local" id="time_block" name="time_block" required style="width: 100%; padding: 10px; border: 1px solid #4e54c8; border-radius: 4px; font-size: 14px; margin-bottom: 20px;">

        <center>
            <button type="submit" style="display: block; margin: 0 auto; padding: 10px 20px; background-color: #4e54c8; color: #fff; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; transition: background-color 0.3s;">
                Confirm Check-Out
            </button>
        </center>
        </form>
    </div>
</div>

</div>
</body>
</html>
