<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobTitle = $_POST['job_title'];
    $department = $_POST['department'];
    $status = $_POST['status'];

    $insertQuery = "INSERT INTO employee_vacancies (job_title, department, status) 
                    VALUES ('$jobTitle', '$department', '$status')";

    if ($conn->query($insertQuery)) {
        echo "<script>alert('Vacancy added successfully'); window.location.href = 'view_employee_vacancies.php';</script>";
    } else {
        echo "<script>alert('Error adding vacancy');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Employee Vacancy</title>
</head>
<body>
    <h1>Add Employee Vacancy</h1>
    <form action="add_employee_vacancy.php" method="POST">
        <label for="job_title">Job Title:</label>
        <input type="text" name="job_title" required>

        <label for="department">Department:</label>
        <input type="text" name="department" required>

        <label for="status">Status:</label>
        <select name="status">
            <option value="Open">Open</option>
            <option value="Closed">Closed</option>
        </select>

        <button type="submit">Add Vacancy</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>
