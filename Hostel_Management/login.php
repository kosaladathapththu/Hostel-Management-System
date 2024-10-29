<?php
session_start();
include 'db_connect.php'; // Include your database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    $query = $conn->prepare("SELECT * FROM residents WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Check if the account is active
            if ($row['status'] === 'active') {
                // Login successful, store resident_id in session
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
