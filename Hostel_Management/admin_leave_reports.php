<?php
session_start();
include 'db_connect.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch the admin name
$admin_id = $_SESSION['admin_id'];
$adminQuery = "SELECT admin_name FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($adminQuery);
if ($stmt) {
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $admin_name = $row['admin_name'];
    } else {
        $admin_name = "Admin"; // Default fallback
    }
    $stmt->close();
} else {
    $admin_name = "Admin"; // Default fallback in case of query failure
}

// Fetch approved leave applications based on the selected period
$filterQuery = "";
$reportTitle = "All Approved Leave Reports";

if (isset($_GET['report_type'])) {
    $report_type = $_GET['report_type'];
    $currentYear = date("Y");
    $currentMonth = date("m");

    if ($report_type == "monthly") {
        $filterQuery = "WHERE MONTH(la.start_date) = $currentMonth AND YEAR(la.start_date) = $currentYear AND la.status = 'approved'";
        $reportTitle = "Monthly Leave Reports (" . date("F Y") . ")";
    } elseif ($report_type == "annual") {
        $filterQuery = "WHERE YEAR(la.start_date) = $currentYear AND la.status = 'approved'";
        $reportTitle = "Annual Leave Reports ($currentYear)";
    }
}

$query = "SELECT la.application_id, la.employee_id, e.name AS employee_name, la.start_date, la.end_date, la.reason 
          FROM leave_applications la
          JOIN employees e ON la.employee_id = e.employee_id
          $filterQuery";
$result = $conn->query($query);

if (!$result) {
    die("Error executing query: " . $conn->error); // Debugging in case of query failure
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Leave Requests</title>
    <link rel="stylesheet" href="view_leave_requests.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; margin: 0; padding: 0; }
        .main-container { padding: 20px; margin-left: 250px; }
        .sidebar { position: fixed; width: 250px; height: 100%; background-color: #2c3e50; color: white; padding: 15px; }
        .sidebar h2, .sidebar ul, .sidebar button { margin: 10px 0; }
        .sidebar h2 { font-size: 1.5em; text-align: center; }
        .profile-info { text-align: center; margin-bottom: 15px; }
        .profile-info p { margin: 0; font-size: 1.2em; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar ul li { margin: 10px 0; }
        .sidebar ul li a { text-decoration: none; color: white; display: block; padding: 10px; border-radius: 5px; }
        .sidebar ul li a:hover { background-color: #34495e; }
        .edit-btn, .logout-btn { width: 100%; background-color: #e74c3c; color: white; text-align: center; padding: 10px; border: none; border-radius: 5px; margin-top: 10px; }
        .edit-btn:hover, .logout-btn:hover { background-color: #c0392b; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: center; }
        th { background-color: #f4f4f4; }
        .btn { padding: 10px 15px; text-decoration: none; border: none; border-radius: 5px; margin-right: 10px; }
        .btn-primary { background-color: #007BFF; color: #fff; }
        .btn-secondary { background-color: #6c757d; color: #fff; }
        .print-btn { margin-top: 20px; }
        @media print {
            body * { visibility: hidden; }
            .main-container, .main-container * { visibility: visible; }
            .sidebar, .profile-info, .breadcrumbs, .print-btn { display: none; }
            section { margin-top: 0; }
            
        }

        
    </style>
</head>
<body>
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

    <div class="main-container">
        <div class="header2">
            <img src="images/header.png" alt="Header Image">
        </div>
        <h2><?php echo $reportTitle; ?></h2>
        <div>
            <a href="admin_leave_reports.php?report_type=monthly" class="btn btn-primary">Monthly Report</a>
            <a href="admin_leave_reports.php?report_type=annual" class="btn btn-secondary">Annual Report</a>
            <button class="btn btn-primary print-btn" onclick="printPage()">Print Report</button>
        </div>

        <section>
            <table>
                <thead>
                    <tr>
                        <th>Application ID</th>
                        <th>Employee Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['application_id']; ?></td>
                                <td><?php echo $row['employee_name']; ?></td>
                                <td><?php echo $row['start_date']; ?></td>
                                <td><?php echo $row['end_date']; ?></td>
                                <td><?php echo htmlspecialchars($row['reason']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No records found for this period.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>

    <script>
        function printPage() {
            const printButton = document.querySelector('.print-btn');
            printButton.style.display = 'none';
            window.print();
            printButton.style.display = 'block';
        }
    </script>
    <?php $conn->close(); ?>
</body>
</html>

