<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to admin login if not logged in
    exit;
}

// Fetch the admin name
$admin_id = $_SESSION['admin_id'];
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

// Fetch pending matrons
$query = "SELECT * FROM Matron_Vacancys WHERE status = 'pending'";
$result = $conn->query($query);

// Approve matron
if (isset($_POST['approve'])) {
    $vacancyId = $_POST['vacancy_id'];

    // Move matron to Matron table
    $selectQuery = "SELECT * FROM Matron_Vacancys WHERE vacancy_id = ?";
    $stmt = $conn->prepare($selectQuery);
    $stmt->bind_param("i", $vacancyId);
    $stmt->execute();
    $vacancyResult = $stmt->get_result();

    if ($vacancyResult->num_rows > 0) {
        $matron = $vacancyResult->fetch_assoc();

        // Insert the matron into the Matrons table
        $insertQuery = "INSERT INTO Matrons (first_name, second_name, email, birth_date, city, password) VALUES (?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ssssss", $matron['first_name'], $matron['second_name'], $matron['email'], $matron['birth_date'], $matron['city'], $matron['password']);
        
        if ($insertStmt->execute()) {
            // Update the admin_id in the job_applications table (foreign key link)
            $updateJobAppQuery = "UPDATE job_applications SET admin_id = ? WHERE vacancy_id = ?";
            $updateJobAppStmt = $conn->prepare($updateJobAppQuery);
            $updateJobAppStmt->bind_param("ii", $admin_id, $vacancyId);
            $updateJobAppStmt->execute();

            // Update status in Matron_Vacancy table
            $updateQuery = "UPDATE Matron_Vacancys SET status = 'approved' WHERE vacancy_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("i", $vacancyId);
            $updateStmt->execute();

            echo "Matron approved successfully!";
        } else {
            echo "Error approving matron: " . $insertStmt->error;
        }

        $insertStmt->close();
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matron Approval</title>
    <link rel="stylesheet" href="admin_approve_matron.css">
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
    <div class="main-container">
        <header>
            <div class="header-left">
                <img src="The_Salvation_Army.png" alt="Logo" class="logo">
            </div>
            <center><h1>Salvation Army Girls Hostel</h1></center>
            <div class="header-right">
                <p>Welcome, <?php echo htmlspecialchars($admin_name); ?></p>
            </div>
        </header>

        <h2>Pending Matron Approvals</h2>
        <div class="breadcrumbs">

            <span class="breadcrumb-separator">|</span>
            <a href="admin_dashboard.php" class="breadcrumb-item">
                <i class="fas fa-home"></i> Admin Dashboard
            </a>
        </div>

        <section>
            <table>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Second Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['vacancy_id']; ?></td>
                    <td><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['second_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <form action="admin_approve_matron.php" method="POST">
                            <input type="hidden" name="vacancy_id" value="<?php echo $row['vacancy_id']; ?>">
                            <button type="submit" name="approve">Approve</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </section>
    </div>

    <?php $conn->close(); ?>
</body>
</html>
