<?php
session_start();
include 'db_connect.php'; // Include your database connection

// Handle registration
if (isset($_POST['register'])) {
    // Collect and sanitize input data
    $firstName = trim($_POST['first_name']);
    $secondName = trim($_POST['second_name']);
    $email = trim($_POST['email']);
    $birthDate = $_POST['birth_date']; // Assuming date format is validated before submission
    $city = trim($_POST['city']);
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists in the Matron_Vacancy table
    $checkEmailQuery = "SELECT * FROM matron_vacancys WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) { // Email not found, proceed with registration

        // Retrieve the first admin's ID to associate with the new matron
        $defaultAdminQuery = "SELECT admin_id FROM admin LIMIT 1";  // Retrieve first admin ID
        $adminResult = $conn->query($defaultAdminQuery);
        $adminId = null;

        if ($adminResult && $adminResult->num_rows > 0) {
            $adminRow = $adminResult->fetch_assoc();
            $adminId = $adminRow['admin_id'];  // Use the first admin's ID
        } else {
            // Handle case where no admin is found
            echo "No admin found in the system. Please contact the system administrator.";
            exit();
        }

        // Insert new matron registration data into the database, including admin_id
        $insertQuery = "INSERT INTO matron_vacancys (first_name, second_name, email, birth_date, city, password, status, admin_id) VALUES (?, ?, ?, ?, ?, ?, 'pending', ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssssssi", $firstName, $secondName, $email, $birthDate, $city, $hashedPassword, $adminId);

        if ($stmt->execute()) {
            echo "Registration successful! Your application is pending admin approval.";
        } else {
            echo "Registration failed: " . $stmt->error;
        }
    } else {
        echo "Email already registered!";
    }

    // Close statement and database connection
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
