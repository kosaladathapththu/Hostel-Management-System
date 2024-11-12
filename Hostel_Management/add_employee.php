<?php
include 'db_connect.php'; // Include database connection

$successMessage = '';
$errorMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $national_id = $_POST['national_id'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];

    $insertQuery = "INSERT INTO employees (name, national_id, email, phone, position, created_at) 
                    VALUES ('$name', '$national_id', '$email', '$phone', '$position', NOW())";

    if ($conn->query($insertQuery) === TRUE) {
        $successMessage = "Employee added successfully.";
    } else {
        $errorMessage = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Employee</title>
    <link rel="stylesheet" href="add_employee.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<header>
    <h1>Salvation Army Girls Hostel - Admin Dashboard</h1>
    <h2>Add Employe<h2>
    <div class="user-info">
        <p>Welcome, <p> 
        <a href="admin_logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<body>
    <h1>Add New Employee</h1><center>

    <?php if (!empty($successMessage)): ?>
        <div class="success-message"><?php echo $successMessage; ?></div>
    <?php elseif (!empty($errorMessage)): ?>
        <div class="error-message"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <form action="add_employee.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" required>

        <label for="national_id">National ID:</label>
        <input type="text" name="national_id" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="phone">Phone:</label>
        <input type="text" name="phone" required>

        <label for="position">Position:</label>
        <input type="text" name="position" required>

        <button type="submit">Add Employee</button>
    </form>

    <a href="view_employee.php">Back to Employee List</a>| |
    <a href="admin_dashboard.php">Admin Dashboard</a></center>
</body>
</html>

<?php $conn->close(); ?>
