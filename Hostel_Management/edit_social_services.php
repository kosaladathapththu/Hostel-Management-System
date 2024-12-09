<?php
session_start();
include 'db_connect.php';

$admin_name = "Admin"; // Default value in case admin_name is not found
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $adminQuery = "SELECT admin_name FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($adminQuery);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $admin_name = $row['admin_name']; // Set admin name if found in database
    }
    $stmt->close();
}

// Fetch social service data
$query = "SELECT * FROM social_service";
$result = $conn->query($query);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Social Services</title>
    <link rel="stylesheet" href="view_social_service.css">
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
            <center><h1>Salvation Army Girls Hostel</h1></center>
            <div class="header-right">
                <p>Welcome, <?php echo htmlspecialchars($admin_name); ?></p>
            </div>
        </header>

        <h2>Social Services List</h2>
        <div class="breadcrumbs">
        <a href="add_social_services.php" class="breadcrumb-item">
                <i class="fas fa-plus"></i>Add social service
</a>
        <span class="breadcrumb-separator">|</span>
            <a href="admin_dashboard.php" class="breadcrumb-item">
                <i class="fas fa-home"></i> Admin Dashboard
            </a>
           

        </div>

        <!-- Social Services Table -->
        <table>
            <thead>
                <tr>
                    <th>Service ID</th>
                    <th>Service Date</th>
                    <th>Province</th>
                    <th>City</th>
                    <th>Street</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['service_id'] . "</td>";
                        echo "<td>" . $row['service_date'] . "</td>";
                        echo "<td>" . $row['service_province'] . "</td>";
                        echo "<td>" . $row['service_city'] . "</td>";
                        echo "<td>" . $row['service_street'] . "</td>";
                        echo "<td>" . $row['service_description'] . "</td>";
                        echo "<td>
                            <a href='edit_social_service.php?id=" . $row['service_id'] . "' class='edit-btn'><i class='fas fa-edit'></i> Edit</a>
                            <a href='delete_social_service.php?id=" . $row['service_id'] . "' class='delete-btn'><i class='fas fa-trash'></i> Delete</a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No social services found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
