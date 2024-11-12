<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if the employee exists
    $query = "SELECT * FROM employees WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $employee = $result->fetch_assoc();

        // Check if the employee is logging in for the first time
        if ($employee['is_password_set'] == 0) {
            // Hash and set the entered password as the permanent password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $update_query = "UPDATE employees SET password = ?, is_password_set = 1 WHERE employee_id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("si", $hashedPassword, $employee['employee_id']);
            $update_stmt->execute();

            // Save the employee's session data
            $_SESSION['employee'] = $employee;
            $_SESSION['employee_logged_in'] = true; // Track login status

            // Redirect to the dashboard
            header("Location: employee_dashboard.php");
            exit();
        }

        // Verify the password for subsequent logins
        if (password_verify($password, $employee['password'])) {
            $_SESSION['employee'] = $employee;
            $_SESSION['employee_logged_in'] = true; // Track login status

            // Check if there is a redirect URL after login
            if (isset($_SESSION['redirect_after_login'])) {
                $redirect_url = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']); // Clear it after use
                header("Location: $redirect_url"); // Redirect to intended page
            } else {
                header("Location: employee_dashboard.php"); // Default redirect
            }
            exit();
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Invalid email or password.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>
    <link rel="stylesheet" href="employee_login.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <h2>Employee Login</h2>
        <form method="POST" action="employee_login.php">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>

