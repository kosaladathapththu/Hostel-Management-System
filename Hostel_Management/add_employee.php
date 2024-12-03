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
    $national_id = $_POST['national_id'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];

    $insertQuery = "INSERT INTO employees (name, national_id, email, phone, position, created_at) 
                    VALUES ('$name', '$national_id', '$email', '$phone', '$position', NOW())";

    if ($conn->query($insertQuery) === TRUE) {
        $successMessage = "Employee added successfully.";
    } else {
        $errorMessage = "Error: " . $conn->error;
    }
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
            <li><a href="view_employee.php"><i class="fas fa-users"></i> Employee Management</a></li>
            <li><a href="view_employee_vacancies.php"><i class="fas fa-briefcase"></i> Employee Vacancies</a></li>
            <li><a href="view_applications.php"><i class="fas fa-file-alt"></i> Job Applications</a></li>
            <li><a href="admin_approve_matron.php"><i class="fas fa-user-check"></i> Matron Applications</a></li>
            <li><a href="view_attendance.php"><i class="fas fa-calendar-check"></i> Attendance Record</a></li>
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
            <h1>Salvation Army Girls Hostel - Admin Dashboard</h1>
            <h2>Add Employee</h2>
            <div class="user-info">
                <p>Welcome, <?php echo htmlspecialchars($admin_name); ?></p>
            </div>
        </header>

        <div class="content">
            <h1>Add New Employee</h1>
            <?php if (!empty($successMessage)): ?>
                <div class="success-message"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php elseif (!empty($errorMessage)): ?>
                <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>

            <center>
                <form action="add_employee.php" method="POST">
                    <label for="name">Name:</label>
                    <input type="text" name="name" required>

                    <label for="national_id">National ID:</label>
                    <input type="text" name="national_id" required>

                    <label for="email">Email:</label>
                    <input type="email" name="email" required>

                    <label for="phone">Phone:</label>
                    <input type="text" name="phone" required>

                    <label for="position">Position:</label>
                    <input type="text" name="position" required>

                    <button type="submit">Add Employee</button>
                </form>

                <div class="breadcrumbs">
                    <a href="view_employee.php" class="breadcrumb-item">
                    <i class="fas fa-arrow-left"></i> Back to Employee List
                    </a>
                <span class="breadcrumb-separator">|</span>
                <a href="admin_dashboard.php" class="breadcrumb-item">
                <i class="fas fa-home"></i> Admin Dashboard
                </a>
</div>
            </center>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
