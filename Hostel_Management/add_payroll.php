<?php
include 'db_connect.php';
session_start();

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
$admin_id = $_SESSION['admin_id']; // Retrieve admin ID from session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $base_salary = $_POST['base_salary'];
    $allowances = $_POST['allowances'];
    $deductions = $_POST['deductions'];
    $total_salary = $base_salary + $allowances - $deductions;
    $salary_date = $_POST['salary_date'];

    // Insert salary details along with admin ID
    $sql = "INSERT INTO salary (employee_id, admin_id, base_salary, allowances, deductions, total_salary, salary_date)
            VALUES ('$employee_id', '$admin_id', '$base_salary', '$allowances', '$deductions', '$total_salary', '$salary_date')";

    if ($conn->query($sql) === TRUE) {
        echo "Salary details added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch employees for the dropdown
$employees = $conn->query("SELECT employee_id, name FROM employees");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Salary</title>
    <link rel="stylesheet" href="add_payroll.css">
    <link rel="stylesheet" href="view_payrol.css?v=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

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



    <br><center>
    <form action="add_payroll.php" method="POST">
        <!-- Employee Dropdown -->
        <label for="employee_id">Employee:</label>
        <select name="employee_id" id="employee_id" required onchange="fetchPosition()">
            <option value="">Select Employee</option>
            <?php while ($emp = $employees->fetch_assoc()): ?>
                <option value="<?php echo $emp['employee_id']; ?>"><?php echo $emp['name']; ?></option>
            <?php endwhile; ?>
        </select>

        <!-- Position Field (Read-Only) -->
        <label for="position">Position:</label>
        <input type="text" id="position" name="position" readonly>

        <!-- Salary Details -->
        <label for="base_salary">Base Salary:</label>
        <input type="number" name="base_salary" required>
        <label for="allowances">Allowances:</label>
        <input type="number" name="allowances">
        <label for="deductions">Deductions:</label>
        <input type="number" name="deductions">
        <label for="salary_date">Salary Date:</label>
        <input type="date" name="salary_date" required>
        
        <button type="submit">Add Salary</button>
    </form></center>
    <center><a href="view_payroll.php" class="dashboard-button">View Payroll</a> | |
    <a href="admin_dashboard.php" class="dashboard-button">Dashboard</a></center>

    <!-- AJAX Script to Fetch Position -->
    <script>
        function fetchPosition() {
            var employeeId = document.getElementById("employee_id").value;
            if (employeeId) {
                $.ajax({
                    url: 'get_employee_details.php',
                    type: 'POST',
                    data: { employee_id: employeeId },
                    success: function(response) {
                        $('#position').val(response);
                    }
                });
            } else {
                $('#position').val('');
            }
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>
