<?php
// Connect to the database
include 'db_connect.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apply for Job</title>
    <link rel="stylesheet" href="apply_employee_vacancies.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <h2>Apply for Job</h2>

    <?php
    // Fetch open job vacancies for dropdown
    $query = "SELECT vacancy_id, job_title FROM employee_vacancies WHERE status = 'Open'";
    $result = mysqli_query($conn, $query);
    ?>

    <form action="submit_application.php" method="POST">
        <label for="vacancy">Select Job Vacancy:</label>
        <select name="vacancy_id" required>
            <?php
            // Populate dropdown with job vacancies
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['vacancy_id'] . "'>" . $row['job_title'] . "</option>";
            }
            ?>
        </select>
        <br><br>

        <label for="name">Name:</label>
        <input type="text" name="applicant_name" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" name="contact_email" required>
        <br><br>

        <label for="phone">Phone:</label>
        <input type="tel" name="contact_phone" required>
        <br><br>

        <label for="cover_letter">Cover Letter:</label><br>
        <textarea name="cover_letter" rows="5" cols="40" required></textarea>
        <br><br>

        <button type="submit">Submit Application</button>
    </form>
</body>
</html>
