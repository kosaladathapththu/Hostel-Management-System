<?php
session_start();

if (!isset($_SESSION['guest_id'])) {
    header("Location: guest_login.php");
    exit();
}

include 'db_connect.php';
$guest_name = $_SESSION['guest_name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Guest Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $guest_name; ?>!</h1>

    <!-- Room Information Section -->
    <section>
        <h2>Room Information</h2>
        
        <!-- View Room Vacancy -->
        <div>
            <h3>View Room Vacancy</h3>
            <!-- Display available rooms -->
            <a href="view_room_vacancy.php">See Available Rooms</a>
        </div>

        <!-- Apply for Room Vacancy -->
        <div>
            <h3>Apply for Room Vacancy</h3>
            <a href="register.php">Submit Room Application</a>
        </div>

        <!-- Rules for Residents -->
        <div>
            <h3>Rules and Regulations for Residents</h3>
            <a href="view_resident_rules.php">Read Resident Rules</a>
        </div>
    </section>

    <!-- Job Opportunities Section -->
    <section>
        <h2>Job Opportunities</h2>
        
        <!-- View Job Vacancy -->
        <div>
            <h3>View Job Vacancies</h3>
            <a href="view_job_vacancy.php">See Open Positions</a>
        </div>

        <!-- Apply for Job -->
        <div>
            <h3>Apply for Job</h3>
            <a href="apply_for_job.php">Submit Job Application</a>
        </div>

        <!-- Rules for Employees -->
        <div>
            <h3>Rules and Regulations for Employees</h3>
            <a href="view_employee_rules.php">Read Employee Rules</a>
        </div>
    </section>

    <br><a href="logout.php">Logout</a>
</body>
</html>
