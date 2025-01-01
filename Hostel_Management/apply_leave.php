<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['employee'])) {
    header("Location: employee_login.php");
    exit;
}
$employee = $_SESSION['employee'];

// Retrieve employee ID from session
$employee_id = $_SESSION['employee']['employee_id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);

    // Insert the leave application into the database
    $query = "INSERT INTO leave_applications (employee_id, start_date, end_date, reason, status) 
    VALUES ($employee_id, '$start_date', '$end_date', '$reason', 'pending')";


    if (mysqli_query($conn, $query)) {
        echo "<p>Leave application submitted successfully!</p>";
    } else {
        echo "<p>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply for Leave</title>
    <link rel="stylesheet" href="employee_view_leave_status.css"> 
    <link rel="stylesheet" href="employee_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<body>
        <!-- Sidebar -->
        <div class="sidebar">
        <div class="logo-container">
            <i class="fas fa-church"></i>
            <h2>Salvation Army<br>Girls Hostel</h2>
        </div>
        <ul class="nav-menu">
            <li><a href="employee_dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="employee_view_leave_status.php"><i class="fas fa-calendar-check"></i><span>Leave Status</span></a></li>
            <li><a href="employee_view_remaining_leave.php"><i class="fas fa-calendar-alt"></i><span>Remaining Leave</span></a></li>
            <li><a href="checkin.php"><i class="fas fa-sign-in-alt"></i><span>Check-in</span></a></li>
            <li><a href="update_ckeckout.php"><i class="fas fa-sign-out-alt"></i><span>Check-out</span></a></li>
            <li><a href="view_attendance.php"><i class="fas fa-clipboard-list"></i><span>Attendance</span></a></li>
            <li><a href="payroll_inquiry.php"><i class="fas fa-money-bill-wave"></i><span>Payroll</span></a></li>
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
    <h2>Apply for leave</h2>
    <div class="breadcrumbs">
    <a href="employee_view_leave_status.php" class="breadcrumb-item">
        <i class="fas fa-backward "></i> Back to leave status
    </a>
    <span class="breadcrumb-separator">|</span>
    <a href="employee_dashboard.php" class="breadcrumb-item">
        <i class="fas fa-home"></i> Dashboard
    </a>
</div>

    <form action="apply_leave.php" method="post">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required><br><br>

        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" required><br><br>

        <label for="reason">Reason for Leave:</label><br>
        <textarea id="reason" name="reason" rows="4" cols="50" required></textarea><br><br>

        <input type="submit" value="Submit">
    </form>
    <br>

</div>
</body>
</html>
