<?php
session_start();
include 'db_connect.php';

// Ensure employee is logged in
if (!isset($_SESSION['employee'])) {
    header("Location: employee_login.php");
    exit();
}

$employee = $_SESSION['employee'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $employee['employee_id'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $new_password = $_POST['new_password'];

    // Update employee details
    $update_query = "UPDATE employees SET name = ?, position = ?, email = ?, phone = ? WHERE employee_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssi", $name, $position, $email, $phone, $employee_id);
    $stmt->execute();

    // If a new password is provided, update it
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_password_query = "UPDATE employees SET password = ? WHERE employee_id = ?";
        $stmt = $conn->prepare($update_password_query);
        $stmt->bind_param("si", $hashed_password, $employee_id);
        $stmt->execute();
    }

    // Redirect to a confirmation page or back to the dashboard
    header("Location: employee_dashboard.php?message=Details updated successfully.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Details</title>
    <link rel="stylesheet" href="employee_change_details.css">
    <link rel="stylesheet" href="employee_view_leave_status.css"> 
    <link rel="stylesheet" href="employee_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
                <!-- Sidebar -->
                <div class="sidebar">
                <div class="logo-container">
        <img src="The_Salvation_Army.png" alt="Logo" class="logo" style="width: 60px; height: 60px;"> 

        <h2>Salvation Army<br>Girls Hostel</h2>
    </div>
        <ul class="nav-menu">
            <li><a href="employee_dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="employee_view_leave_status.php"><i class="fas fa-calendar-check"></i><span>Leave Status</span></a></li>
            <li><a href="employee_view_remaining_leave.php"><i class="fas fa-calendar-alt"></i><span>Remaining Leave</span></a></li>
            <li><a href="checkin.php"><i class="fas fa-sign-in-alt"></i><span>Check-in</span></a></li>
            <li><a href="update_ckeckout.php"><i class="fas fa-sign-out-alt"></i><span>Check-out</span></a></li>
            <li><a href="view_attendance.php"><i class="fas fa-clipboard-list"></i><span>Attendance</span></a></li>

            <li><a href="view_paysheet.php"><i class="fas fa-file-invoice-dollar"></i><span>Paysheet</span></a></li>
            <li><a href="employee_change_details.php"><i class="fas fa-user-edit"></i><span>Profile</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
    <div class="header" style="width: 100%;">
            <h1>Welcome, <?php echo $employee['name']; ?></h1>
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <a href="employee_logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    <h2>Edit details</h2>
    <div class="breadcrumbs">

    <span class="breadcrumb-separator">|</span>
    <a href="employee_dashboard.php" class="breadcrumb-item">
        <i class="fas fa-home"></i> Back to Dashboard
    </a>
</div>
  
    <center><form action="" method="post">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $employee['name']; ?>" required><br>

        <label>Position:</label>
        <input type="text" name="position" value="<?php echo $employee['position']; ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $employee['email']; ?>" required><br>

        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo $employee['phone']; ?>" required><br>

        <label>New Password (leave blank if you don't want to change it):</label>
        <input type="password" name="new_password"><br>
        </form></center>

        <button type="submit" style="display: block; margin: 20px auto; padding: 12px 20px; background-color: #4e54c8; color: #fff; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; text-align: center;">
    Update Details
</button>

   
</div>
</body>
</html>
