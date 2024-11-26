<?php
session_start();
include 'db_connect.php'; // Include your database connection

// Handle registration
if (isset($_POST['register'])) {
    $firstName = $_POST['first_name'];
    $secondName = $_POST['second_name'];
    $email = $_POST['email'];
    $birthDate = $_POST['birth_date'];
    $city = $_POST['city'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists in Matron_Vacancy
    $checkEmailQuery = "SELECT * FROM matron_vacancys WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) { // Email not found, proceed to registration
        $query = "INSERT INTO matron_vacancys(first_name, second_name, email, birth_date, city, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssss", $firstName, $secondName, $email, $birthDate, $city, $password);

        if ($stmt->execute()) {
            echo "Registration successful! Your application is pending admin approval.";
        } else {
            echo "Registration failed: " . $stmt->error;
        }
    } else {
        echo "Email already registered!";
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
    <title>Matron Registration</title>
    <link rel="stylesheet" href="matron_register.css">
</head>
<body>
    <h2>Matron Registration</h2>
    <form action="matron_register.php" method="POST">
        <label>First Name:</label>
        <input type="text" name="first_name" required>
        <label>Last Name:</label>
        <input type="text" name="second_name" required>
        <label>Email (used as username):</label>
        <input type="email" name="email" required>
        <label>Birth Date:</label>
        <input type="date" name="birth_date" required>
        <label>City:</label>
        <input type="text" name="city" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit" name="register">Register</button>
    </form>
    <p>Already registered? <a href="matron_auth.php">Login here</a></p>
</body>
</html>
