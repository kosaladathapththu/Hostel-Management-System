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
    <title>View Job Vacancies</title>
    <link rel="stylesheet" href="guest_dashboard.css">
    <link rel="stylesheet" href="employee_vacancies.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>

<div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="logo-container">
                <img src="The_Salvation_Army.png" alt="Salvation Army Logo" class="logo"><br><br>


</div>
<div><center><h3 styles="margin-bottom:20px;">Salvation Army <br>Girl's Hostel</h3></center>
</div>
            
            
            <nav class="sidebar-menu">
            <ul>
                    <li class="active">
                        <a href="guest_dashboard.php">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="social_services.php">
                            <i class="fas fa-gopuram"></i>
                            <span>Social Services</span>
                        </a>
                    </li>
                    <li>
                        <a href="view_room_vacancy.php">
                            <i class="fas fa-bed "></i>
                            <span>Room Vacancies</span>
                        </a>
                    </li>
                    <li>
                        <a href="register.php">
                            <i class="fas fa-file"></i>
                            <span>Apply Residency</span>
                        </a>
                    </li>
                    <li>
                        <a href="employee_vacancies.php">
                            <i class="fas fa-id-card "></i>
                            <span>Job Vacancies</span>
                        </a>
                    </li>
                    <li>
                        <a href="apply_for_job.php">
                            <i class="fas fa-square"></i>
                            <span>Apply Employee</span>
                        </a>
                    </li>
                    <li>
                        <a href="#profile">
                            <i class="fas fa-user"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="guest_logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <header class="dashboard-header">
                <div class="header-left">
                    <h1>Welcome, <?php echo htmlspecialchars($guest_name); ?>!</h1>
                </div>
                <div class="header-right">
                    <div class="notification-icon">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="user-profile">
                        <img src="guest.jpg" alt="Profile Picture">
                    </div>
                </div>
                
            </header>
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
