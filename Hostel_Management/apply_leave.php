<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['employee'])) {
    // Redirect to login if not logged in
    header("Location: employee_login.php");
    exit;
}

// Retrieve employee ID from session
$employee_id = $_SESSION['employee']['employee_id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);

    // Insert the leave application into the database
    $query = "INSERT INTO leave_applications (employee_id, start_date, end_date, reason, status) 
              VALUES ($employee_id, '$start_date', '$end_date', '$reason', 'pending')";

    if (mysqli_query($conn, $query)) {
        echo "<p>Leave application submitted successfully!</p>";
    } else {
        echo "<p>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply for Leave</title>
</head>
<body>
    <h1>Apply for Leave</h1>
    <form action="apply_leave.php" method="post">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required><br><br>

        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" required><br><br>

        <label for="reason">Reason for Leave:</label><br>
        <textarea id="reason" name="reason" rows="4" cols="50" required></textarea><br><br>

        <input type="submit" value="Submit">
    </form>
    <br>
    <button onclick="window.location.href='employee_dashboard.php'">Back to Dashboard</button>
</body>
</html>
