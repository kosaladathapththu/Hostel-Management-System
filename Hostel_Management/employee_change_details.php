<?php
session_start();
include 'db_connect.php';

// Ensure employee is logged in
if (!isset($_SESSION['employee'])) {
    header("Location: employee_login.php");
    exit();
}

$employee = $_SESSION['employee'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $employee['employee_id'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $new_password = $_POST['new_password'];

    // Update employee details
    $update_query = "UPDATE employees SET name = ?, position = ?, email = ?, phone = ? WHERE employee_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssi", $name, $position, $email, $phone, $employee_id);
    $stmt->execute();

    // If a new password is provided, update it
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_password_query = "UPDATE employees SET password = ? WHERE employee_id = ?";
        $stmt = $conn->prepare($update_password_query);
        $stmt->bind_param("si", $hashed_password, $employee_id);
        $stmt->execute();
    }

    // Redirect to a confirmation page or back to the dashboard
    header("Location: employee_dashboard.php?message=Details updated successfully.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Details</title>
</head>
<body>
    <h1>Change Your Details</h1>
    <form action="" method="post">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $employee['name']; ?>" required><br>

        <label>Position:</label>
        <input type="text" name="position" value="<?php echo $employee['position']; ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $employee['email']; ?>" required><br>

        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo $employee['phone']; ?>" required><br>

        <label>New Password (leave blank if you don't want to change it):</label>
        <input type="password" name="new_password"><br>

        <button type="submit">Update Details</button>
    </form>
</body>
</html>
