<?php
include 'db_connect.php';

// Fetch salary records with employee details
$query = "SELECT s.salary_id, e.name AS employee_name, e.position, s.base_salary, s.allowances, s.deductions, s.total_salary, s.salary_date 
          FROM salary s 
          JOIN employees e ON s.employee_id = e.employee_id";
$salaryRecords = $conn->query($query);

// Handle Delete Operation
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM salary WHERE salary_id = $delete_id";
    if ($conn->query($deleteQuery) === TRUE) {
        echo "Salary record deleted successfully.";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Payroll</title>
    <link rel="stylesheet" href="view_payrol.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<header>
    <h1>Salvation Army Girls Hostel - Admin Dashboard</h1>
<h2>View Payroll System</h2>
     
    <div class="user-info">
        <p>Welcome, <p> 
        <a href="admin_logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<body><br>
    <center>
    
    <table border="1">
        <tr>
            <th>Employee Name</th>
            <th>Position</th>
            <th>Base Salary</th>
            <th>Allowances</th>
            <th>Deductions</th>
            <th>Total Salary</th>
            <th>Salary Date</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $salaryRecords->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['employee_name']; ?></td>
                <td><?php echo $row['position']; ?></td>
                <td><?php echo $row['base_salary']; ?></td>
                <td><?php echo $row['allowances']; ?></td>
                <td><?php echo $row['deductions']; ?></td>
                <td><?php echo $row['total_salary']; ?></td>
                <td><?php echo $row['salary_date']; ?></td>
                <td>
                    <a href="edit_payroll.php?salary_id=<?php echo $row['salary_id']; ?>">Edit</a> | 
                    <a href="view_payroll.php?delete_id=<?php echo $row['salary_id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        
    </table></center><br>
    <center><a href="add_payroll.php" class="add-btn">Add New Salary</a> | |
    <a href="admin_dashboard.php" class="dashboard-button">Dashboard</a></center>
</body>
</html>

<?php $conn->close(); ?>
