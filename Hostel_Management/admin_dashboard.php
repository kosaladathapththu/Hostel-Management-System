<?php
session_start();
include 'db_connect.php'; // Include database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch admin details from the database
$admin_id = $_SESSION['admin_id']; // Assuming admin ID is stored in session
$query = "SELECT admin_name FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($admin_name);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
        <p class="admin-info">Welcome, <?php echo $admin_name; ?></p>
        <a href="admin_edit_profile.php" class="edit-btn">Edit Profile</a>
        <a class="logout-btn" href="admin_logout.php">Logout</a>
    </div>

    <section class="dashboard-section">
        <h2>Admin Functions</h2>
        <div class="controls-grid">
            <!-- Employee Management -->
            <h3>Employee Management</h3>
            <a href="view_employee.php" class="control-btn">View Employee</a>
            <a href="view_employee_vacancies.php" class="control-btn">Employee Vacancy</a>

            <!-- Employee Reports -->
            <h3>Employee Reports</h3>
            <a href="generate_employee_report_monthly.php" class="control-btn">Generate Monthly Employee Report</a>
            <a href="generate_employee_report_annual.php" class="control-btn">Generate Annual Employee Report</a>
            

            <!-- Attendance and Leave -->
            <h3>Attendance and Leave Management</h3>
            <a href="view_attendance.php" class="control-btn">View Attendance Record</a>
            <a href="view_leave_requests.php" class="control-btn">View Leave Requests</a>
            <a href="approve_leave.php" class="control-btn">Approve Leave Request</a>
            <a href="decline_leave.php" class="control-btn">Decline Leave Request</a>

            <!-- Leave Reports -->
            <h3>Leave Reports</h3>
            <a href="generate_leave_report_monthly.php" class="control-btn">Generate Monthly Leave Report</a>
            <a href="generate_leave_report_annual.php" class="control-btn">Generate Annual Leave Report</a>

            <!-- Payroll Management -->
            <h3>Payroll Management</h3>
            <a href="view_payroll.php" class="control-btn">View Payroll System</a>
            <a href="calculate_leave_deduction.php" class="control-btn">Calculate Leave Deduction</a>
            <a href="calculate_salary.php" class="control-btn">Calculate Salary</a>
            <a href="send_notification.php" class="control-btn">Send Notification to Employee</a>

            <!-- Payroll Reports -->
            <h3>Payroll Reports</h3>
            <center><a href="generate_payroll_reports.php" class="control-btn">Payroll Report</a></center>
            
        </div>
    </section>
</body>
</html>
