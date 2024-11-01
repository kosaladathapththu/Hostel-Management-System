<?php
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM guests WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            $_SESSION['guest_id'] = $row['guest_id'];
            $_SESSION['guest_name'] = $row['name'];
            header("Location: guest_dashboard.php");
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Email not registered!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Guest Login</title>
</head>
<body>
    <h2>Guest Login</h2>
    <form method="POST" action="">
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    <p>Don’t have an account? <a href="guest_register.php">Register here</a>.</p>
</body>
</html>
