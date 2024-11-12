<?php
session_start();
include 'db_connect.php'; // Include database connection

// Check if the user is logged in as an employee
if (!isset($_SESSION['employee']['employee_id'])) {
    header("Location: employee_login.php"); // Redirect to login if not logged in
    exit();
}

// Get employee_id from session
$employee_id = $_SESSION['employee']['employee_id'];

// Fetch the latest paysheet for the logged-in employee
$query = "
    SELECT salary_date, base_salary, allowances, deductions, total_salary 
    FROM salary 
    WHERE employee_id = ? 
    ORDER BY salary_date DESC 
    LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$paysheet = $result->fetch_assoc();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Paysheet</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include CSS for styling -->
</head>
<body>
    <div class="content">
        <h1>Your Paysheet</h1>

        <?php if ($paysheet): ?>
            <table border="1" cellpadding="10">
                <tr>
                    <th>Pay Date</th>
                    <td><?php echo date('Y-m-d', strtotime($paysheet['salary_date'])); ?></td>
                </tr>
                <tr>
                    <th>Basic Salary</th>
                    <td><?php echo "Rs." . number_format($paysheet['base_salary'], 2); ?></td>
                </tr>
                <tr>
                    <th>Allowances</th>
                    <td><?php echo "Rs." . number_format($paysheet['allowances'], 2); ?></td>
                </tr>
                <tr>
                    <th>Deductions</th>
                    <td><?php echo "Rs." . number_format($paysheet['deductions'], 2); ?></td>
                </tr>
                <tr>
                    <th>Total Salary</th>
                    <td><?php echo "Rs." . number_format($paysheet['total_salary'], 2); ?></td>
                </tr>
            </table>
        <?php else: ?>
            <p>No paysheet data available for this month.</p>
        <?php endif; ?>

        <br>
        <button onclick="window.location.href='employee_dashboard.php'">Back to Dashboard</button>
    </div>
</body>
</html>
