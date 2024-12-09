<?php
session_start();
include 'db_connect.php';

// Initialize admin_name with a default value
$admin_name = "Admin"; // Default value
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

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];

// Fetch the service details for editing
if (isset($_GET['id'])) {
    $service_id = $_GET['id'];
    
    $sql = "SELECT * FROM social_service WHERE service_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();

    if (!$service) {
        echo "Service not found!";
        exit;
    }
    
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $service_date = $_POST['service_date'];
    $service_province = $_POST['service_province'];
    $service_city = $_POST['service_city'];
    $service_street = $_POST['service_street'];
    $service_description = $_POST['service_description'];

    // Update the service in the database
    $updateQuery = "UPDATE social_service SET service_date = ?, service_province = ?, service_city = ?, service_street = ?, service_description = ? WHERE service_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssssi", $service_date, $service_province, $service_city, $service_street, $service_description, $service_id);

    if ($stmt->execute()) {
        header("Location: view_social_service.php");
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Social Service</title>
    <link rel="stylesheet" href="add_social_services.css">
    <link rel="stylesheet" href="view_social_service.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        <h2>Edit Social Service</h2>
        <div class="breadcrumbs" style="margin-top: 5px">

      
            <span class="breadcrumb-separator">|</span>
            <a href="admin_dashboard.php" class="breadcrumb-item">
                <i class="fas fa-home"></i> Admin Dashboard
            </a>

        </div>
        <center><form action="" method="POST" style="max-width: 800px; width: 100%; margin: 10px auto; padding: 20px; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <label for="service_date">Service Date:</label>
            <input type="date" name="service_date" value="<?php echo htmlspecialchars($service['service_date']); ?>" required>

            <label for="service_province">Province:</label>
            <input type="text" name="service_province" value="<?php echo htmlspecialchars($service['service_province']); ?>" required>

            <label for="service_city">City:</label>
            <input type="text" name="service_city" value="<?php echo htmlspecialchars($service['service_city']); ?>" required>

            <label for="service_street">Street:</label>
            <input type="text" name="service_street" value="<?php echo htmlspecialchars($service['service_street']); ?>" required>

            <label for="service_description">Description:</label>
            <textarea name="service_description" required><?php echo htmlspecialchars($service['service_description']); ?></textarea>

            <button type="submit">Update Service</button>
        </form></center>
    </div>
</body>
</html>
