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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Admin Panel</h2>
        <div class="profile-info">
            <p><i class="fas fa-user"></i> <?php echo $admin_name; ?></p>
        </div>
        <ul>
            <li><a href="view_employee.php"><i class="fas fa-users"></i> Employee Management</a></li>
            <li><a href="view_employee_vacancies.php"><i class="fas fa-briefcase"></i> Employee Vacancies</a></li>
            <li><a href="view_applications.php"><i class="fas fa-file-alt"></i> Job Applications</a></li>
            <li><a href="admin_approve_matron.php"><i class="fas fa-user-check"></i> Matron Applications</a></li>
            <li><a href="view_attendance.php"><i class="fas fa-calendar-check"></i> Attendance Record</a></li>
            <li><a href="view_leave_requests.php"><i class="fas fa-envelope"></i> Leave Requests</a></li>
            <li><a href="view_payroll.php"><i class="fas fa-money-check-alt"></i> Payroll System</a></li>
            <li><a href="generate_payroll_reports.php"><i class="fas fa-chart-line"></i> Payroll Report</a></li>
            <li><a href="view_social_service.php"><i class="fas fa-server "></i>View social services</a></li>
            <li><a href="add_social_services.php"><i class="fas fa-bars"></i>Add social services</a></li>
        </ul>
        <button onclick="window.location.href='admin_edit_profile.php'" class="edit-btn"><i class="fas fa-user-edit"></i> Edit Profile</button>
        <button onclick="window.location.href='admin_logout.php'" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
    <header>
        <div class="header-left">
            <img src="The_Salvation_Army.png" alt="Logo" class="logo"> 
        </div>
        <center><b><h1>Salvation army Girsl hostel</h1></b></center>
        <div class="header-right">
            <p>Welcome, <?php echo $admin_name; ?></p>
        </div>
    </header>
        <section class="dashboard-section">
            <!-- Employee Management -->
            <div class="dashboard-box">
                <h2><i class="fas fa-users"></i> Employee Management</h2>
                <button onclick="window.location.href='view_employee.php'" class="control-btn">View Employee</button>
                <button onclick="window.location.href='view_employee_vacancies.php'" class="control-btn">Employee Vacancy</button>
                <button onclick="window.location.href='view_applications.php'" class="control-btn">View Job Applications</button>
                <button onclick="window.location.href='admin_approve_matron.php'" class="control-btn">Matron Applications</button>
            </div>
            <div class="dashboard-box">
                <h2><i class="fas fa-industry "></i> Leave Management</h2>
                <button onclick="window.location.href='view_leave_requests.php'" class="control-btn">View leave Requests</button>
                <button onclick="window.location.href='view_attendance.php'" class="control-btn">Attendance Record</button>
            </div>
            <div class="dashboard-box">
                <h2><i class="fas fa-life-ring "></i> Social Awareness</h2>
                <button onclick="window.location.href='add_social_services.php'" class="control-btn">Add social services</button>
                <button onclick="window.location.href='view_social_services.php'" class="control-btn">View social services</button>
            </div>
            <div class="dashboard-box">
                <h2><i class="fas fa-credit-card-alt"></i> Payroll Management</h2>
                <button onclick="window.location.href='view_payroll.php'" class="control-btn">View Payroll</button>
                <button onclick="window.location.href='generate_payroll_reports.php'" class="control-btn">Payroll reports</button>
            </div>
        </section>
    </div>
</body>
</html>