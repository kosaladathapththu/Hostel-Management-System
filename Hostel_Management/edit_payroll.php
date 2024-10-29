<?php
include 'db_connect.php';

if (isset($_GET['salary_id'])) {
    $salary_id = $_GET['salary_id'];

    // Fetch current salary details
    $query = "SELECT * FROM salary WHERE salary_id = $salary_id";
    $result = $conn->query($query);
    $salary = $result->fetch_assoc();

    // Fetch employee details for dropdown
    $employees = $conn->query("SELECT employee_id, name FROM employees");
}

// Update salary record
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $salary_id = $_POST['salary_id'];
    $employee_id = $_POST['employee_id'];
    $base_salary = $_POST['base_salary'];
    $allowances = $_POST['allowances'];
    $deductions = $_POST['deductions'];
    $total_salary = $base_salary + $allowances - $deductions;
    $salary_date = $_POST['salary_date'];

    $updateQuery = "UPDATE salary 
                    SET employee_id = '$employee_id', base_salary = '$base_salary', allowances = '$allowances', deductions = '$deductions', 
                        total_salary = '$total_salary', salary_date = '$salary_date' 
                    WHERE salary_id = '$salary_id'";

    if ($conn->query($updateQuery) === TRUE) {
        echo "Salary record updated successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Salary</title>
</head>
<body>
    <h2>Edit Salary</h2>
    <form action="edit_salary.php" method="POST">
        <input type="hidden" name="salary_id" value="<?php echo $salary['salary_id']; ?>">

        <!-- Employee Dropdown -->
        <label for="employee_id">Employee:</label>
        <select name="employee_id" required>
            <?php while ($emp = $employees->fetch_assoc()): ?>
                <option value="<?php echo $emp['employee_id']; ?>" <?php if ($emp['employee_id'] == $salary['employee_id']) echo 'selected'; ?>>
                    <?php echo $emp['name']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <!-- Salary Details -->
        <label for="base_salary">Base Salary:</label>
        <input type="number" name="base_salary" value="<?php echo $salary['base_salary']; ?>" required>
        <label for="allowances">Allowances:</label>
        <input type="number" name="allowances" value="<?php echo $salary['allowances']; ?>">
        <label for="deductions">Deductions:</label>
        <input type="number" name="deductions" value="<?php echo $salary['deductions']; ?>">
        <label for="salary_date">Salary Date:</label>
        <input type="date" name="salary_date" value="<?php echo $salary['salary_date']; ?>" required>
        
        <button type="submit">Update Salary</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>
