<?php
include 'db_connect.php';

// Check if the session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$admin_name = "Admin"; // Default fallback
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];

    // Fetch admin name
    $adminQuery = "SELECT admin_name FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($adminQuery);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $admin_name = $row['admin_name'];
    } else {
        $admin_name = "Admin (Not Found)";
    }

    $stmt->close();
}

// Fetch salary records with employee details
$query = "SELECT s.salary_id, e.name AS employee_name, e.position, s.base_salary, s.allowances, s.deductions, s.total_salary, s.salary_date 
          FROM salary s 
          JOIN employees e ON s.employee_id = e.employee_id";
$salaryRecords = $conn->query($query);

// Handle Delete Operation
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM salary WHERE salary_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Salary record deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payroll</title>
    <link rel="stylesheet" href="view_payrol.css?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body><center>
    <!-- Wrapper -->
    <div class="wrapper">
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
                
                    <h1>Salvation Army Girl's Hostel</h1>
            
            </header>

            <!-- Breadcrumbs -->
            <div class="breadcrumbs">
           
                <a href="add_payroll.php" class="breadcrumb-item">
                    <i class="fas fa-plus"></i> Add New Payroll
                </span>
                <span class="breadcrumb-separator">|</span>
                <a href="admin_dashboard.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Admin Dashboard
                </a>

            </div>

            <!-- Payroll Table -->
            <div class="table-wrapper">
                
                    <table>
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Position</th>
                                <th>Base Salary</th>
                                <th>Allowances</th>
                                <th>Deductions</th>
                                <th>Total Salary</th>
                                <th>Salary Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $salaryRecords->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['employee_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                                    <td><?php echo htmlspecialchars($row['base_salary']); ?></td>
                                    <td><?php echo htmlspecialchars($row['allowances']); ?></td>
                                    <td><?php echo htmlspecialchars($row['deductions']); ?></td>
                                    <td><?php echo htmlspecialchars($row['total_salary']); ?></td>
                                    <td><?php echo htmlspecialchars($row['salary_date']); ?></td>
                                    <td>
                                        <a href="edit_payroll.php?salary_id=<?php echo $row['salary_id']; ?>">Edit</a> |
                                        <a href="view_payroll.php?delete_id=<?php echo $row['salary_id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </center>
                <br>
                
            </div>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
