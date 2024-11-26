<?php
session_start();
include 'db_connect.php'; // Include your database connection script

// Check if the user is logged in as an employee
if (!isset($_SESSION['employee'])) { // Use 'employee' session variable
    header("Location: employee_login.php"); // Redirect to login if not logged in
    exit;
}

// Get the employee ID from the session
$employee_id = $_SESSION['employee']['employee_id']; // Access employee_id from the 'employee' array

// Fetch the remaining leave balance for the logged-in employee
$query = "SELECT leave_balance FROM employees WHERE employee_id = $employee_id";
$result = mysqli_query($conn, $query);

// Check if the employee record was found
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $remaining_leave = $row['leave_balance'];
} else {
    $remaining_leave = "N/A";  // Display "N/A" if no record is found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Remaining Leave</title>
    <link rel="stylesheet" href="employee_view_remaining_leave.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        
    </style>
</head>
<body>
    <div class="content">
        <h1>Your Remaining Leave</h1>
        <p>You have <span class="leave-balance"><?php echo $remaining_leave; ?></span> days of leave remaining.</p>
        <br>
        <button onclick="window.location.href='employee_dashboard.php'">Back to Dashboard</button>
    </div>
</body>
</html>
