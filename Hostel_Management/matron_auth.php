<?php
session_start();
include 'db_connect.php'; // Include your database connection

// Handle login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the matron is approved
    $query = "SELECT * FROM Matrons WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $matron = $result->fetch_assoc();

        if (password_verify($password, $matron['password'])) {
            $_SESSION['matron_id'] = $matron['matron_id'];
            $_SESSION['matron_name'] = $matron['first_name'];
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "No account found with that email or not yet approved!";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matron Login</title>
    <link rel="stylesheet" href="matron_auth.css">
</head>
<body>
    
    <form action="matron_auth.php" method="POST">
    <h2>Matron Login</h2>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit" name="login">Login</button>
        <p>If not registered? <a href="matron_register.php">Register here</a></p>
    </form>
    
</body>
</html>
