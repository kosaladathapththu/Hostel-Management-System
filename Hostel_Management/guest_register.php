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
        echo "<script>alert('Email already registered!');</script>";
    } else {
        // Insert new guest
        $query = "INSERT INTO guests (name, email, password, phone) VALUES ('$name', '$email', '$password', '$phone')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Registration successful! Redirecting to login...');</script>";
            header("refresh:2;url=guest_login.php");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Registration</title>
    <link rel="stylesheet" href="guest_register.css">
    <script src="guest_register.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="image-section">
            <!-- Placeholder image for demonstration -->
            <img src="guest_register.png" alt="Hostel Image">
        </div>
        <div class="form-section">
            <h2>Create account</h2>
            <form method="POST" action="" onsubmit="return validateForm()">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required><br>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required><br>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required><br>

                <label for="phone">Phone:</label>
                <input type="text" name="phone" id="phone" required><br>

                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="guest_login.php">Login here</a>.</p>
        </div>
    </div>
</body>
</html>
