<?php
include 'db_connect.php';

if (isset($_GET['vacancy_id'])) {
    $vacancyId = $_GET['vacancy_id'];
    $vacancyQuery = "SELECT * FROM employee_vacancies WHERE vacancy_id = $vacancyId";
    $vacancyResult = $conn->query($vacancyQuery);
    $vacancy = $vacancyResult->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vacancyId = $_POST['vacancy_id'];
    $jobTitle = $_POST['job_title'];
    $department = $_POST['department'];
    $status = $_POST['status'];

    $updateQuery = "UPDATE employee_vacancies 
                    SET job_title = '$jobTitle', department = '$department', status = '$status' 
                    WHERE vacancy_id = $vacancyId";

    if ($conn->query($updateQuery)) {
        echo "<script>alert('Vacancy updated successfully'); window.location.href = 'view_employee_vacancies.php';</script>";
    } else {
        echo "<script>alert('Error updating vacancy');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Employee Vacancy</title>
    <link rel="stylesheet" href="update_employee_vacancy.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> 
</head>

<header>
    <h1>Salvation Army Girls Hostel - Admin Dashboard</h1>
<h2>Update Employee Vacancy</h2>
     
    <div class="user-info">
        <p>Welcome, <p> 
        <a href="admin_logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<body><br>
    <center>
    <form action="update_employee_vacancy.php" method="POST">
        <input type="hidden" name="vacancy_id" value="<?php echo $vacancy['vacancy_id']; ?>">

        <label for="job_title">Job Title:</label>
        <input type="text" name="job_title" value="<?php echo $vacancy['job_title']; ?>" required>

        <label for="department">Department:</label>
        <input type="text" name="department" value="<?php echo $vacancy['department']; ?>" required>

        <label for="status">Status:</label>
        <select name="status">
            <option value="Open" <?php if ($vacancy['status'] === 'Open') echo 'selected'; ?>>Open</option>
            <option value="Closed" <?php if ($vacancy['status'] === 'Closed') echo 'selected'; ?>>Closed</option>
        </select>

        <button type="submit">Update Vacancy</button></center>
        <a href="admin_dashboard.php" class="dashboard-button">Dashboard</a> 
    </form>
</body>
</html>

<?php $conn->close(); ?>
