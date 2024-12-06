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
    $emp_gender = $_POST['emp_gender'];
    $emp_addr_street = $_POST['emp_addr_street'];
    $emp_addr_city = $_POST['emp_addr_city'];
    $emp_addr_province = $_POST['emp_addr_province'];
    $status = $_POST['status'];
    $new_password = $_POST['new_password'];

    // Update employee details
    $update_query = "UPDATE employees SET name = ?, position = ?, email = ?, phone = ?, emp_gender = ?, emp_addr_street = ?, emp_addr_city = ?, emp_addr_province = ?, status = ? WHERE employee_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssssssssi", $name, $position, $email, $phone, $emp_gender, $emp_addr_street, $emp_addr_city, $emp_addr_province, $status, $employee_id);
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
    <title>Change Your Details</title>
    <link rel="stylesheet" href="employee_change_details.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        <label>Gender:</label>
        <select name="emp_gender" required>
            <option value="Male" <?php echo ($employee['emp_gender'] == 'Male' ? 'selected' : ''); ?>>Male</option>
            <option value="Female" <?php echo ($employee['emp_gender'] == 'Female' ? 'selected' : ''); ?>>Female</option>
            <option value="Other" <?php echo ($employee['emp_gender'] == 'Other' ? 'selected' : ''); ?>>Other</option>
        </select><br>

        <label>Street Address:</label>
        <input type="text" name="emp_addr_street" value="<?php echo $employee['emp_addr_street']; ?>"><br>

        <label>City:</label>
        <input type="text" name="emp_addr_city" value="<?php echo $employee['emp_addr_city']; ?>"><br>

        <label>Province:</label>
        <input type="text" name="emp_addr_province" value="<?php echo $employee['emp_addr_province']; ?>"><br>

        <label>Status:</label>
        <select name="status" required>
            <option value="1" <?php echo ($employee['status'] == 1 ? 'selected' : ''); ?>>Active</option>
            <option value="0" <?php echo ($employee['status'] == 0 ? 'selected' : ''); ?>>Inactive</option>
        </select><br>

        <label>New Password (leave blank if you don't want to change it):</label>
        <input type="password" name="new_password"><br>

        <button type="submit">Update Details</button>
    </form>
</body>
</html>
