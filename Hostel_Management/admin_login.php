<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch admin details
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();

        // Check if it’s the first login (password is set as "1234")
        if ($admin['password'] == "1234") {
            // Hash and save the entered password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Update password in the database
            $updateQuery = "UPDATE admin SET password = ? WHERE admin_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("si", $hashedPassword, $admin['admin_id']);

            if ($updateStmt->execute()) {
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['admin_logged_in'] = true;
                header("Location: admin_dashboard.php");
                exit();
            } else {
                echo "Error updating password. Please try again.";
            }
            $updateStmt->close();

        } else {
            // If it's not the first login, verify the entered password with the stored hash
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['admin_logged_in'] = true; // To use in admin_dashboard.php
                header("Location: admin_dashboard.php");
                exit();
            } else {
                echo "Invalid password!";
            }
        }
    } else {
        echo "Admin not found!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin_login.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    
    <form method="POST">
    <h2>Admin Login</h2><br>
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
