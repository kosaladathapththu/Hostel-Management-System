<?php
include 'db_connect.php';

$query = "SELECT * FROM employees";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee List</title>
    <link rel="stylesheet" href="view_employee.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<header>
    <h1>Salvation Army Girls Hostel - Admin Dashboard</h1>
    <h2>View Employees</h2>
    <div class="user-info">
        <p>Welcome, <p>
        
        <a href="admin_logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<body>
    <h1>Employee List</h1>


    <center><table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>National ID</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Position</th>
            <th>Actions</th>
        </tr>

        <?php while ($employee = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $employee['employee_id']; ?></td>  <!-- Corrected this line -->
                <td><?php echo $employee['name']; ?></td>
                <td><?php echo $employee['national_id']; ?></td> <!-- Make sure this column exists -->
                <td><?php echo $employee['email']; ?></td>
                <td><?php echo $employee['phone']; ?></td>
                <td><?php echo $employee['position']; ?></td>
                <td>
                    <a href="edit_employee.php?id=<?php echo $employee['employee_id']; ?>">Edit</a> |
                    <a href="delete_employee.php?id=<?php echo $employee['employee_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table><br>
    <a href="add_employee.php">Add New Employee</a>| |<a href="admin_dashboard.php">Admin Dashboard</a></center>
    

</body>
</html>

<?php $conn->close(); ?>
