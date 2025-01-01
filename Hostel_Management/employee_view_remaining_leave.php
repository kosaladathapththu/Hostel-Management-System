<?php
session_start();
include 'db_connect.php'; 


if (!isset($_SESSION['employee'])) { 
    header("Location: employee_login.php"); 
    exit;
}
$employee = $_SESSION['employee'];

$employee_id = $_SESSION['employee']['employee_id']; 

// Fetch the remaining leave balance for the logged-in employee
$query = "SELECT leave_balance FROM employees WHERE employee_id = $employee_id";
$result = mysqli_query($conn, $query);

// Check if the employee record was found
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $remaining_leave = $row['leave_balance'];
} else {
    $remaining_leave = "N/A";  // Display "N/A" if no record is found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Remaining Leave</title>
    <link rel="stylesheet" href="employee_view_remaining_leave.css">
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
    <div class="header" style="width: 100%; margin-top: -300px;">
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
 
    <div class="breadcrumbs">

    <span class="breadcrumb-separator">|</span>
    <a href="employee_dashboard.php" class="breadcrumb-item">
        <i class="fas fa-home"></i> Dashboard
    </a>
</div>

    <center><div class="content">
        <h1>Your Remaining Leave</h1>
        <p>You have <span class="leave-balance"><?php echo $remaining_leave; ?></span> days of leave remaining.</p>
        <br></center>

    </div>
</div>
</body>
</html>
