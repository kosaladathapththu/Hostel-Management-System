<?php
session_start(); // Start the session

include 'db_connect.php';

// Check if admin_id is set in the session
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];

    // Fetch current admin details
    $query = "SELECT admin_name, username, email FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $admin_name = $admin['admin_name'];
    } else {
        // Admin record not found
        $admin_name = "Unknown Admin";
    }
    $stmt->close();
} else {
    // Redirect to login page if admin_id is not set in session
    header("Location: admin_login.php");
    exit();
}

// Fetch employee details
$id = $_GET['id']; // Ensure you validate this in production code to prevent SQL injection
$employeeQuery = "SELECT * FROM employees WHERE employee_id = $id";
$employeeResult = $conn->query($employeeQuery);
$employee = $employeeResult->fetch_assoc();

// Check if the employee exists
if (!$employee) {
    echo "Employee not found!";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $national_id = $_POST['national_id'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];
    $emp_gender = $_POST['emp_gender']; // New field
    $emp_addr_street = $_POST['emp_addr_street']; // New field
    $emp_addr_city = $_POST['emp_addr_city']; // New field
    $emp_addr_province = $_POST['emp_addr_province']; // New field
    $leave_balance = $_POST['leave_balance']; // New field

    // Update query with new fields
    $updateQuery = "UPDATE employees SET 
                    name = '$name', 
                    national_id = '$national_id', 
                    email = '$email', 
                    phone = '$phone', 
                    position = '$position',
                    emp_gender = '$emp_gender',
                    emp_addr_street = '$emp_addr_street',
                    emp_addr_city = '$emp_addr_city',
                    emp_addr_province = '$emp_addr_province',
                    leave_balance = '$leave_balance'
                    WHERE employee_id = $id";

    if ($conn->query($updateQuery) === TRUE) {
        header("Location: view_employee.php");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Employee</title>
    <link rel="stylesheet" href="edit_employee.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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

        <br><center>
        <form method="POST" action="">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($employee['name']); ?>" required>

    <label for="national_id">National ID:</label>
    <input type="text" id="national_id" name="national_id" value="<?php echo htmlspecialchars($employee['national_id']); ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required>

    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($employee['phone']); ?>" required>

    <label for="position">Position:</label>
    <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($employee['position']); ?>" required>

    <label for="emp_gender">Gender:</label>
    <select id="emp_gender" name="emp_gender" required>
        <option value="Male" <?php echo ($employee['emp_gender'] == 'Male' ? 'selected' : ''); ?>>Male</option>
        <option value="Female" <?php echo ($employee['emp_gender'] == 'Female' ? 'selected' : ''); ?>>Female</option>
        <option value="Other" <?php echo ($employee['emp_gender'] == 'Other' ? 'selected' : ''); ?>>Other</option>
    </select>

    <label for="emp_addr_street">Street Address:</label>
    <input type="text" id="emp_addr_street" name="emp_addr_street" value="<?php echo htmlspecialchars($employee['emp_addr_street']); ?>">

    <label for="emp_addr_city">City:</label>
    <input type="text" id="emp_addr_city" name="emp_addr_city" value="<?php echo htmlspecialchars($employee['emp_addr_city']); ?>">

    <label for="emp_addr_province">Province:</label>
    <input type="text" id="emp_addr_province" name="emp_addr_province" value="<?php echo htmlspecialchars($employee['emp_addr_province']); ?>">

    <label for="leave_balance">Leave Balance:</label>
    <input type="number" id="leave_balance" name="leave_balance" value="<?php echo htmlspecialchars($employee['leave_balance']); ?>" required>

    <input type="submit" value="Update Employee">
</form>



            <a href="view_employee.php">Back to Employee List</a> | | <a href="admin_dashboard.php">Admin Dashboard</a></center>
    </div>
</body>
</html>

<?php $conn->close(); ?>
