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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <h2>Add Salary</h2>
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
    </form>

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
