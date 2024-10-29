<?php
include 'db_connect.php';

$id = $_GET['id'];
// Use employee_id instead of id
$employeeQuery = "SELECT * FROM employees WHERE employee_id = $id"; 
$employeeResult = $conn->query($employeeQuery);
$employee = $employeeResult->fetch_assoc();

// Check if the employee exists
if (!$employee) {
    echo "Employee not found!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $national_id = $_POST['national_id'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];

    // Update query should also use employee_id
    $updateQuery = "UPDATE employees SET name = '$name', national_id = '$national_id', 
                    email = '$email', phone = '$phone', position = '$position' WHERE employee_id = $id"; 

    if ($conn->query($updateQuery) === TRUE) {
        header("Location: view_employee.php");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Employee</title>
    <link rel="stylesheet" href="edit_employee.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<header>
    <h1>Salvation Army Girls Hostel - Admin Dashboard</h1>
    <h2>Edit Employe<h2>
    <div class="user-info">
        <p>Welcome, <p> 
        <a href="admin_logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<body>
   <br><center>

    <form action="" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo $employee['name']; ?>" required>

        <label for="national_id">National ID:</label>
        <input type="text" name="national_id" value="<?php echo $employee['national_id']; ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $employee['email']; ?>" required>

        <label for="phone">Phone:</label>
        <input type="text" name="phone" value="<?php echo $employee['phone']; ?>" required>

        <label for="position">Position:</label>
        <input type="text" name="position" value="<?php echo $employee['position']; ?>" required>

        <button type="submit">Update Employee</button>
    </form>

    <a href="view_employee.php">Back to Employee List</a> | | <a href="admin_dashboard.php">Admin Dashboard</a></center>
</body>
</html>

<?php $conn->close(); ?>
