<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $base_salary = $_POST['base_salary'];
    $allowances = $_POST['allowances'];
    $deductions = $_POST['deductions'];
    $total_salary = $base_salary + $allowances - $deductions;
    $salary_date = $_POST['salary_date'];

    $sql = "INSERT INTO salary (employee_id, base_salary, allowances, deductions, total_salary, salary_date)
            VALUES ('$employee_id', '$base_salary', '$allowances', '$deductions', '$total_salary', '$salary_date')";

    if ($conn->query($sql) === TRUE) {
        echo "Salary details added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch employees for the dropdown
$employees = $conn->query("SELECT employee_id, name FROM employees");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Salary</title>
    <link rel="stylesheet" href="add_payroll.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<header>
    <h1>Salvation Army Girls Hostel - Admin Dashboard</h1>
<h2>Add Payroll</h2>
     
    <div class="user-info">
        <p>Welcome, <p> 
        <a href="admin_logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<body>
    <br><center>
    <form action="add_payroll.php" method="POST">
        <!-- Employee Dropdown -->
        <label for="employee_id">Employee:</label>
        <select name="employee_id" id="employee_id" required onchange="fetchPosition()">
            <option value="">Select Employee</option>
            <?php while ($emp = $employees->fetch_assoc()): ?>
                <option value="<?php echo $emp['employee_id']; ?>"><?php echo $emp['name']; ?></option>
            <?php endwhile; ?>
        </select>

        <!-- Position Field (Read-Only) -->
        <label for="position">Position:</label>
        <input type="text" id="position" name="position" readonly>

        <!-- Salary Details -->
        <label for="base_salary">Base Salary:</label>
        <input type="number" name="base_salary" required>
        <label for="allowances">Allowances:</label>
        <input type="number" name="allowances">
        <label for="deductions">Deductions:</label>
        <input type="number" name="deductions">
        <label for="salary_date">Salary Date:</label>
        <input type="date" name="salary_date" required>
        
        <button type="submit">Add Salary</button>
    </form></center>
    <center><a href="view_payroll.php" class="dashboard-button">View Payroll</a> | |
    <a href="admin_dashboard.php" class="dashboard-button">Dashboard</a></center>

    <!-- AJAX Script to Fetch Position -->
    <script>
        function fetchPosition() {
            var employeeId = document.getElementById("employee_id").value;
            if (employeeId) {
                $.ajax({
                    url: 'get_employee_details.php',
                    type: 'POST',
                    data: { employee_id: employeeId },
                    success: function(response) {
                        $('#position').val(response);
                    }
                });
            } else {
                $('#position').val('');
            }
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>
