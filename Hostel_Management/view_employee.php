<?php
session_start();
include 'db_connect.php';

// Check if admin is logged in
$admin_name = "Admin";
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $adminQuery = "SELECT admin_name FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($adminQuery);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $admin_name = $row['admin_name'];
    }
    $stmt->close();
}

// Update leave balance to 5 days per month
$updateLeaveBalanceQuery = "UPDATE employees SET leave_balance = 5 WHERE leave_balance > 5";
$conn->query($updateLeaveBalanceQuery);

// Fetch employees
$query = "SELECT * FROM employees";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee List</title>
    <link rel="stylesheet" href="view_employee.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Admin Panel</h2>
        <div class="profile-info">
            <p><i class="fas fa-user"></i> <?php echo htmlspecialchars($admin_name); ?></p>
        </div>
        <ul>
             <li><a href="admin_dashboard.php"><i class="fas fa-dashboard"></i> Dashboard</a></li>
            <li><a href="view_employee.php"><i class="fas fa-users"></i> Employee Management</a></li>
            <li><a href="view_employee_vacancies.php"><i class="fas fa-briefcase"></i> Employee Vacancies</a></li>
            <li><a href="view_applications.php"><i class="fas fa-file-alt"></i> Job Applications</a></li>
            <li><a href="admin_approve_matron.php"><i class="fas fa-user-check"></i> Matron Applications</a></li>
            <li><a href="view_attendance_by_admin.php"><i class="fas fa-calendar-check"></i> Attendance Record</a></li>
            <li><a href="view_leave_requests.php"><i class="fas fa-envelope"></i> Leave Requests</a></li>
            <li><a href="view_payroll.php"><i class="fas fa-money-check-alt"></i> Payroll System</a></li>
            <li><a href="generate_payroll_reports.php"><i class="fas fa-chart-line"></i> Payroll Report</a></li>
            <li><a href="view_social_service.php"><i class="fas fa-server"></i> View Social Services</a></li>
            <li><a href="add_social_services.php"><i class="fas fa-bars"></i> Add Social Services</a></li>
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
            <center><b><h1>Salvation Army Girls Hostel</h1></b></center>
            <div class="header-right">
                <p>Welcome, <?php echo htmlspecialchars($admin_name); ?></p>
            </div>
        </header>

        <h1>Employee List</h1>
        <div class="breadcrumbs">
            <a href="add_employee.php" class="breadcrumb-item">
                <i class="fas fa-plus"></i> Add new employee
            </a>
            <span class="breadcrumb-separator">|</span>
            <a href="admin_dashboard.php" class="breadcrumb-item">
                <i class="fas fa-home"></i> Admin Dashboard
            </a>

            <span class="breadcrumb-separator">|</span>
            <a href="employee_report.php" class="breadcrumb-item">
                <i class="fas fa-print"></i> Emplyee Report
            </a>
        </div>

        <center>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>National ID</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Position</th>
                <th>Gender</th>
                
                <th>City</th>
                
                
               
                <th>Actions</th>
            </tr>

            <?php while ($employee = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $employee['employee_id']; ?></td>
                    <td><?php echo $employee['name']; ?></td>
                    <td><?php echo $employee['national_id']; ?></td>
                    <td><?php echo $employee['email']; ?></td>
                    <td><?php echo $employee['phone']; ?></td>
                    <td><?php echo $employee['position']; ?></td>
                    <td><?php echo $employee['emp_gender']; ?></td>
                    
                    <td><?php echo $employee['emp_addr_city']; ?></td>
                    
                    
                    
                    <td>
                        <a href="edit_employee.php?id=<?php echo $employee['employee_id']; ?>">Edit</a> |
                        <a href="delete_employee.php?id=<?php echo $employee['employee_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <br>
        </center>
    </div>
</body>
</html>

<?php $conn->close(); ?>
