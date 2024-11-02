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
    <!-- Header section -->
    <div class="header">
        <h1 class="header-title">Admin Dashboard</h1>
        <div class="admin-info">
            <p>Welcome, <?php echo $admin_name; ?></p>
            <button onclick="window.location.href='admin_edit_profile.php'" class="edit-btn">Edit Profile</button>
            <button onclick="window.location.href='admin_logout.php'" class="logout-btn">Logout</button>
        </div>
    </div>

    <!-- Dashboard Section -->
    <section class="dashboard-section">
        <div class="dashboard-container">
            <!-- Employee Management -->
            <div class="dashboard-box">
                <h2>Employee Management</h2>
                <button onclick="window.location.href='view_employee.php'" class="control-btn">View Employee</button>
                <button onclick="window.location.href='view_employee_vacancies.php'" class="control-btn">Employee Vacancy</button>
            </div>

            <!-- Employee Reports -->
            <div class="dashboard-box">
                <h2>Employee Reports</h2>
                <button onclick="window.location.href='generate_employee_report_monthly.php'" class="control-btn">Generate Monthly Employee Report</button>
                <button onclick="window.location.href='generate_employee_report_annual.php'" class="control-btn">Generate Annual Employee Report</button>
            </div>

            <!-- Attendance and Leave Management -->
            <div class="dashboard-box">
                <h2>Attendance and Leave Management</h2>
                <button onclick="window.location.href='view_attendance.php'" class="control-btn">View Attendance Record</button>
                <button onclick="window.location.href='view_leave_requests.php'" class="control-btn">View Leave Requests</button>
                <button onclick="window.location.href='approve_leave.php'" class="control-btn">Approve Leave Request</button>
                <button onclick="window.location.href='decline_leave.php'" class="control-btn">Decline Leave Request</button>
            </div>

            <!-- Leave Reports -->
            <div class="dashboard-box">
                <h2>Leave Reports</h2>
                <button onclick="window.location.href='generate_leave_report_monthly.php'" class="control-btn">Generate Monthly Leave Report</button>
                <button onclick="window.location.href='generate_leave_report_annual.php'" class="control-btn">Generate Annual Leave Report</button>
            </div>

            <!-- Payroll Management -->
            <div class="dashboard-box">
                <h2>Payroll Management</h2>
                <button onclick="window.location.href='view_payroll.php'" class="control-btn">View Payroll System</button>
                <button onclick="window.location.href='calculate_leave_deduction.php'" class="control-btn">Calculate Leave Deduction</button>
                <button onclick="window.location.href='add_payroll.php'" class="control-btn">Calculate Salary</button>
                <button onclick="window.location.href='send_notification.php'" class="control-btn">Send Notification to Employee</button>
            </div>

            <!-- Payroll Reports -->
            <div class="dashboard-box">
                <h2>Payroll Reports</h2>
                <button onclick="window.location.href='generate_payroll_reports.php'" class="control-btn">Payroll Report</button>
            </div>
        </div>
    </section>
</body>
</html>
