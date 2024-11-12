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
    $checkEmailQuery = "SELECT * FROM Matron_Vacancys WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) { // Email not found, proceed to registration
        $query = "INSERT INTO Matron_Vacancys (first_name, second_name, email, birth_date, city, password) VALUES (?, ?, ?, ?, ?, ?)";
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
    <title>Matron Registration & Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Matron Registration</h2>
    <form action="matron_auth.php" method="POST">
        <label>First Name:</label>
        <input type="text" name="first_name" required>
        <label>Second Name:</label>
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

    <h2>Matron Login</h2>
    <form action="matron_auth.php" method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>
