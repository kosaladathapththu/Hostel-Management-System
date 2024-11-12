<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statement to fetch supplier data securely
    $sql = "SELECT * FROM suppliers WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $supplier = $result->fetch_assoc();

        // Check if it's the first login (password is NULL)
        if (is_null($supplier['password'])) {
            // Hash and save the entered password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Update the password in the database
            $updateQuery = "UPDATE suppliers SET password = ? WHERE supplier_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("si", $hashedPassword, $supplier['supplier_id']);

            if ($updateStmt->execute()) {
                $_SESSION['supplier_id'] = $supplier['supplier_id'];
                header("Location: supplier_dashboard.php");
                exit();
            } else {
                echo "Error updating password. Please try again.";
            }
            $updateStmt->close();
        } else {
            // Verify entered password with the stored hash
            if (password_verify($password, $supplier['password'])) {
                $_SESSION['supplier_id'] = $supplier['supplier_id'];
                header("Location: supplier_dashboard.php");
                exit();
            } else {
                echo "Incorrect password.";
            }
        }
    } else {
        echo "Username not found.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Supplier Login</title>
    <link rel="stylesheet" href="supplier_login.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    
    <form method="post" action="">
    <B><h1><diV>Suplier Login</h1></div></B><br>
        <label>Username:</label>
        <input type="text" name="username" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
