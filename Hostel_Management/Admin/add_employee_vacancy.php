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
    <link rel="stylesheet" href="add_employee_vacancy.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> 
</head>

<header>
    <h1>Salvation Army Girls Hostel - Admin Dashboard</h1>
<h2>Add Employee Vacancy</h2>
     
    <div class="user-info">
        <p>Welcome, <p> 
        <a href="admin_logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<body><center><br>
    
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
        <a href="admin_dashboard.php" class="dashboard-button">Dashboard</a> 
    </form>
</body></center>
</html>

<?php $conn->close(); ?>
