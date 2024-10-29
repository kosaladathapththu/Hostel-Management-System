<?php
include 'db_connect.php'; // Database connection

// Fetch vacancies
$vacanciesQuery = "SELECT * FROM employee_vacancies ORDER BY created_at DESC";
$vacanciesResult = $conn->query($vacanciesQuery);

// Handle Delete
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM employee_vacancies WHERE vacancy_id = $deleteId";
    if ($conn->query($deleteQuery)) {
        echo "<script>alert('Vacancy deleted successfully'); window.location.href = 'view_employee_vacancies.php';</script>";
    } else {
        echo "<script>alert('Error deleting vacancy');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Vacancies</title>
    <link rel="stylesheet" href="view_employee_vacancies.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> 
</head>
<body>
<header>
    <h1>Salvation Army Girls Hostel - Admin Dashboard</h1>
    <h2>Employe Vacancy<h2>
    <div class="user-info">
        <p>Welcome, <p> 
        <a href="admin_logout.php" class="logout-btn">Logout</a>
    </div>
</header>

    <section>
        
        <table>
            <tr>
                <th>Job Title</th>
                <th>Department</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($vacancy = $vacanciesResult->fetch_assoc()): ?>
            <tr>
                <td><?php echo $vacancy['job_title']; ?></td>
                <td><?php echo $vacancy['department']; ?></td>
                <td><?php echo $vacancy['status']; ?></td>
                <td>
                    <a href="update_employee_vacancy.php?vacancy_id=<?php echo $vacancy['vacancy_id']; ?>">Edit</a> |
                    <a href="view_employee_vacancies.php?delete_id=<?php echo $vacancy['vacancy_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table><br>
        <a href="add_employee_vacancy.php" class="btn">Add New Vacancy</a>
        <a href="admin_dashboard.php" class="btn">Dashboard</a>
    </section>

    <?php $conn->close(); ?>
</body>
</html>
