<?php
session_start();
include 'db_connect.php'; // Include database connection

// Check if the user is logged in as an employee
if (!isset($_SESSION['employee']['employee_id'])) {
    header("Location: employee_login.php"); // Redirect to login if not logged in
    exit();
}

$employee = $_SESSION['employee'];
$employee_id = $_SESSION['employee']['employee_id'];

// Fetch the latest paysheet for the logged-in employee
$query = "
    SELECT salary_date, base_salary, allowances, deductions, total_salary 
    FROM salary 
    WHERE employee_id = ? 
    ORDER BY salary_date DESC 
    LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$paysheet = $result->fetch_assoc();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Paysheet</title>
    <link rel="stylesheet" href="styles.css">
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
    <h2>Your Paysheet</h2>
    <div class="breadcrumbs">

    <span class="breadcrumb-separator">|</span>
    <a href="employee_dashboard.php" class="breadcrumb-item">
        <i class="fas fa-home"></i> Back to Dashboard
    </a>
</div>
    <div class="content">


        <?php if ($paysheet): ?>
            <center><table border="1" cellpadding="10">
                <tr>
                    <th>Pay Date</th>
                    <td><?php echo date('Y-m-d', strtotime($paysheet['salary_date'])); ?></td>
                </tr>
                <tr>
                    <th>Basic Salary</th>
                    <td><?php echo "Rs." . number_format($paysheet['base_salary'], 2); ?></td>
                </tr>
                <tr>
                    <th>Allowances</th>
                    <td><?php echo "Rs." . number_format($paysheet['allowances'], 2); ?></td>
                </tr>
                <tr>
                    <th>Deductions</th>
                    <td><?php echo "Rs." . number_format($paysheet['deductions'], 2); ?></td>
                </tr>
                <tr>
                    <th>Total Salary</th>
                    <td><?php echo "Rs." . number_format($paysheet['total_salary'], 2); ?></td>
                </tr>
            </table></center>
        <?php else: ?>
            <p>No paysheet data available for this month.</p>
        <?php endif; ?>

        <br>

    </div>
        </div>
</body>
</html>
