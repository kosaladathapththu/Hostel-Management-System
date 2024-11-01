<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone = $_POST['phone'];

    // Check if the email is already registered
    $query = "SELECT * FROM guests WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "Email already registered!";
    } else {
        // Insert new guest
        $query = "INSERT INTO guests (name, email, password, phone) VALUES ('$name', '$email', '$password', '$phone')";
        
        if (mysqli_query($conn, $query)) {
            echo "Registration successful! Redirecting to login...";
            header("refresh:2;url=guest_login.php");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Guest Registration</title>
</head>
<body>
    <h2>Guest Registration</h2>
    <form method="POST" action="">
        Name: <input type="text" name="name" required><br>
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        Phone: <input type="text" name="phone" required><br>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="guest_login.php">Login here</a>.</p>
</body>
</html>
