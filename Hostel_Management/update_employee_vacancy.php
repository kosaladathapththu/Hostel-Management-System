<?php
include 'db_connect.php';
session_start(); // Start the session

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];

// Fetch the admin name using their ID
$adminQuery = "SELECT admin_name FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($adminQuery);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $admin_name = $row['admin_name'];
} else {
    $admin_name = "Admin"; // Default fallback
}

$stmt->close();

// Fetch the vacancy details
if (isset($_GET['vacancy_id'])) {
    $vacancyId = $_GET['vacancy_id'];
    $vacancyQuery = "SELECT * FROM employee_vacancies WHERE vacancy_id = ?";
    $stmt = $conn->prepare($vacancyQuery);
    $stmt->bind_param("i", $vacancyId);
    $stmt->execute();
    $vacancyResult = $stmt->get_result();
    $vacancy = $vacancyResult->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vacancyId = $_POST['vacancy_id'];
    $jobTitle = $_POST['job_title'];
    $department = $_POST['department'];
    $status = $_POST['status'];

    $updateQuery = "UPDATE employee_vacancies 
                    SET job_title = ?, department = ?, status = ? 
                    WHERE vacancy_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssi", $jobTitle, $department, $status, $vacancyId);

    if ($stmt->execute()) {
        echo "<script>alert('Vacancy updated successfully'); window.location.href = 'view_employee_vacancies.php';</script>";
    } else {
        echo "<script>alert('Error updating vacancy');</script>";
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
    <title>Update Employee Vacancy</title>
    <link rel="stylesheet" href="edit_employee.css">
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

        <h2>Update Employee Vacancy</h2>

        <div class="breadcrumbs">
            <a href="view_employee_vacancies.php" class="breadcrumb-item">
                <i class="fas fa-arrow-left"></i> Back to Vacancy List
            </a>
            <span class="breadcrumb-separator">|</span>
            <a href="admin_dashboard.php" class="breadcrumb-item">
                <i class="fas fa-home"></i> Admin Dashboard
            </a>
        </div>

        <form action="update_employee_vacancy.php" method="POST">
            <input type="hidden" name="vacancy_id" value="<?php echo $vacancy['vacancy_id']; ?>">

            <label for="job_title">Job Title:</label>
            <input type="text" name="job_title" value="<?php echo htmlspecialchars($vacancy['job_title']); ?>" required>

            <label for="department">Department:</label>
            <input type="text" name="department" value="<?php echo htmlspecialchars($vacancy['department']); ?>" required>

            <label for="status">Status:</label>
            <select name="status">
                <option value="Open" <?php if ($vacancy['status'] === 'Open') echo 'selected'; ?>>Open</option>
                <option value="Closed" <?php if ($vacancy['status'] === 'Closed') echo 'selected'; ?>>Closed</option>
            </select>

            <button type="submit">Update Vacancy</button>
        </form>
    </div>
</body>
</html>

