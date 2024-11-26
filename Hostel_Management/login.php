<?php
session_start();
include 'db_connect.php'; // Include your database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to prevent SQL injection
    $query = "SELECT * FROM residents WHERE username = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        // Log the error and do not display it to the user
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        die("An error occurred. Please try again later.");
    }

    // Bind parameters and execute
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Check if the account is active
            if ($row['status'] === 'active') {
                // Regenerate session ID for security
                session_regenerate_id(true);
                // Login successful, store session variables
                $_SESSION['resident_id'] = $row['id']; // Using resident_id as session variable
                $_SESSION['resident_name'] = $row['name']; // Store resident's name for dashboard display
                header('Location: resident_dashboard.php');
                exit();
            } else {
                // Account is pending approval
                $error = "Your account is awaiting approval from the Matron.";
            }
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <h2>Resident Login</h2>
        <form action="login.php" method="POST" class="login-form">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
