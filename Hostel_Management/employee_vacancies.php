<?php
// Connect to the database
include 'db_connect.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Job Vacancies</title>
    <link rel="stylesheet" href="view_room_vacancy.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<header>
    <h1>Salvation Army Girls Hostel - Guest Dashboard</h1>
    <h2> View Employee Vacancy</h2>
    <div class="user-info">
        <p>Welcome, </p>
        <br><a href="guest_logout.php">Logout</a>
    </div>
</header>

<body>
    <h2>Job Vacancies</h2>

    <?php
    // Fetch job vacancies that are open
    $query = "SELECT * FROM employee_vacancies WHERE status = 'Open'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Job Title</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Posted Date</th>
                </tr>";
        
        // Display each job vacancy
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . $row['job_title'] . "</td>
                    <td>" . $row['department'] . "</td>
                    <td>" . $row['status'] . "</td>
                    <td>" . date('Y-m-d', strtotime($row['created_at'])) . "</td>
                  </tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>No job vacancies available at the moment.</p>";
    }

    ?>

</body>
</html>
