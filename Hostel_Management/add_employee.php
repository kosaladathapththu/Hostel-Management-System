<?php
include 'db_connect.php'; // Include database connection

$successMessage = '';
$errorMessage = '';
$admin_name = "Admin"; // Default fallback

// Check if the session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fetch admin name from the database
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];

    // Query to fetch the admin name
    $adminQuery = "SELECT admin_name FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($adminQuery);
    $stmt->bind_param("i", $admin_id); // Bind the admin_id to the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $admin_name = $row['admin_name']; // Update admin_name from the database
    } else {
        $admin_name = "Admin (Not Found)"; // Handle case where admin_id is invalid
    }

    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $emp_gender = $_POST['emp_gender'];
    $position = $_POST['position'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $emp_addr_street = $_POST['emp_addr_street'];
    $emp_addr_city = $_POST['emp_addr_city'];
    $emp_addr_province = $_POST['emp_addr_province'];

    // Default values for other fields
    $status = 1; // Default active status
    $national_id = $_POST['national_id']; // Added national ID from form

    $insertQuery = "INSERT INTO employees (
                        name, 
                        emp_gender, 
                        position, 
                        email, 
                        phone, 
                        emp_addr_street, 
                        emp_addr_city, 
                        emp_addr_province, 
                        status, 
                        created_at, 
                        national_id
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";

    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param(
        "sssssssssi",
        $name, 
        $emp_gender, 
        $position, 
        $email, 
        $phone, 
        $emp_addr_street, 
        $emp_addr_city, 
        $emp_addr_province, 
        $status, 
        $national_id
    );

    if ($stmt->execute()) {
        $successMessage = "Employee added successfully.";
    } else {
        $errorMessage = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <link rel="stylesheet" href="add_employee.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
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
            <center><b><h1>Salvation Army Girl's Hostel</h1></b></center>
        
            <div class="header-right">
                <p>Welcome, <?php echo $admin_name; ?></p>
            </div>
        </header>
        <div class="breadcrumbs">
            <a href="view_employee.php" class="breadcrumb-item">
                <i class="fas fa-arrow-left"></i> Back to Employee List
            </a>
        </div>

        <div class="content">
            <?php if (!empty($successMessage)): ?>
                <div class="success-message"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php elseif (!empty($errorMessage)): ?>
                <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; 
            ?>

            <center>
                <form action="add_employee.php" method="POST">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="emp_gender">Gender:</label>
                    <select id="emp_gender" name="emp_gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>

                    <label for="position">Position:</label>
                    <input type="text" id="position" name="position" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" required>

                    <label for="emp_addr_street">Street Address:</label>
                    <input type="text" id="emp_addr_street" name="emp_addr_street">

                    <label for="emp_addr_city">City:</label>
                    <input type="text" id="emp_addr_city" name="emp_addr_city">

                    <label for="emp_addr_province">Province:</label>
                    <input type="text" id="emp_addr_province" name="emp_addr_province">

                    <label for="national_id">National ID:</label>
                    <input type="text" id="national_id" name="national_id" required>

                    <button type="submit">Save Employee</button>
                </form>
            </center>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
