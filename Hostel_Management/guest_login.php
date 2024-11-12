<?php
include 'db_connect.php';
session_start();

$error_message = '';

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
            exit();
        } else {
            $error_message = "Incorrect password!";
        }
    } else {
        $error_message = "Email not registered!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Login</title>
    <link rel="stylesheet" href="guest_login.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Guest Login</h2>
            <form method="POST" action="">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
                
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                
                <button type="submit">Login</button>
            </form>
            <p>Don’t have an account? <a href="guest_register.php">Register here</a>.</p>
        </div>
    </div>

    <!-- JavaScript for Client-side Validation -->
    <script src="guest_login.js"></script>

    <!-- PHP Error Handling Alert -->
    <?php if (!empty($error_message)): ?>
        <script>
            alert("<?php echo $error_message; ?>");
        </script>
    <?php endif; ?>
</body>
</html>
