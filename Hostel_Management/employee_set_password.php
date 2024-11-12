<?php
session_start();
include 'db_connect.php';

// Check if employee ID exists in session
if (!isset($_SESSION['temp_employee_id'])) {
    header("Location: employee_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    $employee_id = $_SESSION['temp_employee_id'];

    // Update password and set is_password_set to 1
    $query = "UPDATE employees SET password = ?, is_password_set = 1 WHERE employee_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $hashed_password, $employee_id);
    $stmt->execute();
    $stmt->close();

    // Remove temp ID and redirect
    unset($_SESSION['temp_employee_id']);
    header("Location: employee_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Set Your Password</title>
</head>
<body>
    <h2>Set Your Password</h2>
    <form method="POST" action="">
        <label>New Password:</label>
        <input type="password" name="new_password" required>
        <br>
        <button type="submit">Set Password</button>
    </form>
</body>
</html>
