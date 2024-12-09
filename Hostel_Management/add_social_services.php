<?php
session_start();
include 'db_connect.php'; // Database connection file

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_date = $_POST['service_date'];
    $service_province = $_POST['service_province'];
    $service_city = $_POST['service_city'];
    $service_street = $_POST['service_street'];
    $service_description = $_POST['service_description'];
    $admin_id = $_SESSION['admin_id'];

    $query = "INSERT INTO social_service(admin_id, service_date, service_province, service_city, service_street, service_description) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssss", $admin_id, $service_date, $service_province, $service_city, $service_street, $service_description);

    if ($stmt->execute()) {
        $success_message = "Service added successfully!";
    } else {
        $error_message = "Failed to add service. Please try again.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Social Services</title>
    <link rel="stylesheet" href="add_social_services.css">
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

    <h2>New Social Service</h2>
    <div class="breadcrumbs">
    <span class="breadcrumb-separator">|</span>
            <a href="edit_social_services.php" class="breadcrumb-item">
                <i class="fas fa-server"></i> Edit Service

      
            <span class="breadcrumb-separator">|</span>
            <a href="admin_dashboard.php" class="breadcrumb-item">
                <i class="fas fa-home"></i> Admin Dashboard
            </a>

        </div>
        <div class="form-container">
            <?php if (isset($success_message)) echo "<p class='success'>$success_message</p>"; ?>
            <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>
            <form method="POST" action="">
                <label for="service_date">Date:</label>
                <input type="date" id="service_date" name="service_date" required>

                <label for="service_province">Province:</label>
    <select id="service_province" name="service_province" onchange="updateDistricts()" required>
    <option value="">--Select Province--</option>
    <option value="Central">Central</option>
    <option value="Eastern">Eastern</option>
    <option value="Northern">Northern</option>
    <option value="Southern">Southern</option>
    <option value="Western">Western</option>
    <option value="North Central">North Central</option>
    <option value="North Western">North Western</option>
    <option value="Uva">Uva</option>
    <option value="Sabaragamuwa">Sabaragamuwa</option>
</select>

<label for="service_city">District:</label>
<select id="service_city" name="service_city" required>
    <option value="">--Select District--</option>
</select>

<script>
    // Object mapping provinces to their districts
    const districtsByProvince = {
        "Central": ["Kandy", "Matale", "Nuwara Eliya"],
        "Eastern": ["Ampara", "Batticaloa", "Trincomalee"],
        "Northern": ["Jaffna", "Kilinochchi", "Mannar", "Vavuniya", "Mullaitivu"],
        "Southern": ["Galle", "Matara", "Hambantota"],
        "Western": ["Colombo", "Gampaha", "Kalutara"],
        "North Central": ["Anuradhapura", "Polonnaruwa"],
        "North Western": ["Kurunegala", "Puttalam"],
        "Uva": ["Badulla", "Moneragala"],
        "Sabaragamuwa": ["Ratnapura", "Kegalle"]
    };

    // Function to update districts based on selected province
    function updateDistricts() {
        const provinceSelect = document.getElementById("service_province");
        const districtSelect = document.getElementById("service_city");
        const selectedProvince = provinceSelect.value;

        // Clear the current district options
        districtSelect.innerHTML = '<option value="">--Select District--</option>';

        // Populate districts if a province is selected
        if (selectedProvince && districtsByProvince[selectedProvince]) {
            districtsByProvince[selectedProvince].forEach(district => {
                const option = document.createElement("option");
                option.value = district;
                option.textContent = district;
                districtSelect.appendChild(option);
            });
        }
    }
</script>
 
                
                <label for="service_street">Street:</label>
                <input type="text" id="service_street" name="service_street" required>

                <label for="service_description">Description:</label>
                <textarea id="service_description" name="service_description" required></textarea>

                <button type="submit">Add Service</button>
            </form>
        </div>
    </div>
</body>
</html>