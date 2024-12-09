<?php
include 'db_connect.php';

// Validate and fetch salary ID from GET
if (isset($_GET['salary_id']) && is_numeric($_GET['salary_id'])) {
    $salary_id = $_GET['salary_id'];

    // Fetch current salary details
    $query = "SELECT * FROM salary WHERE salary_id = $salary_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $salary = $result->fetch_assoc();
    } else {
        echo "<p style='color: red;'>Salary record not found for ID $salary_id.</p>";
        exit;
    }

    // Fetch employee details for the dropdown
    $employees = $conn->query("SELECT employee_id, name FROM employees");
} else {
    echo "<p style='color: red;'>No valid salary ID provided!</p>";
    exit;
}

// Handle salary update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $salary_id = $_POST['salary_id'];
    $employee_id = $_POST['employee_id'];
    $base_salary = $_POST['base_salary'];
    $allowances = $_POST['allowances'];
    $deductions = $_POST['deductions'];
    $total_salary = $base_salary + $allowances - $deductions;
    $salary_date = $_POST['salary_date'];

    // Update salary record in the database
    $updateQuery = "UPDATE salary 
                    SET employee_id = '$employee_id', 
                        base_salary = '$base_salary', 
                        allowances = '$allowances', 
                        deductions = '$deductions', 
                        total_salary = '$total_salary', 
                        salary_date = '$salary_date' 
                    WHERE salary_id = '$salary_id'";

    if ($conn->query($updateQuery) === TRUE) {
        echo "<p style='color: green;'>Salary record updated successfully.</p>";
    } else {
        echo "<p style='color: red;'>Error updating record: " . $conn->error . "</p>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Salary</title>
    <link rel="stylesheet" href="edit_payroll.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <h1>Salvation Army Girls Hostel - Admin Dashboard</h1>
    <h2>Edit Payroll</h2>
    <div class="user-info">
        <p>Welcome, Admin</p>
        <a href="admin_logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<center>
    <form action="edit_salary.php?salary_id=<?php echo $salary_id; ?>" method="POST">
        <!-- Hidden Salary ID -->
        <input type="hidden" name="salary_id" value="<?php echo $salary_id; ?>">

        <!-- Employee Dropdown -->
        <label for="employee_id">Employee:</label>
        <select name="employee_id" required>
            <?php if ($employees) : ?>
                <?php while ($emp = $employees->fetch_assoc()) : ?>
                    <option value="<?php echo $emp['employee_id']; ?>" 
                        <?php echo ($emp['employee_id'] == $salary['employee_id']) ? 'selected' : ''; ?>>
                        <?php echo $emp['name']; ?>
                    </option>
                <?php endwhile; ?>
            <?php endif; ?>
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

        <!-- Submit Button -->
        <button type="submit">Update Salary</button>
    </form>

    <!-- Navigation Buttons -->
    <center>
        <a href="view_payroll.php" class="dashboard-button">View Payroll</a> | 
        <a href="admin_dashboard.php" class="dashboard-button">Dashboard</a>
    </center>
</center>
</body>
</html>


<?php $conn->close(); ?>
