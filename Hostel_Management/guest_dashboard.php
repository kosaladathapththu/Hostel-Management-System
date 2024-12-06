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
    <link rel="stylesheet" href="guest_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
    /* Snow animation styles */
    .snowflake {
        position: fixed;
        top: -10px;
        z-index: 9999;
        user-select: none;
        cursor: default;
        animation-name: snowfall;
        animation-duration: 10s;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
        opacity: 0.7;
    }

    @keyframes snowfall {
        0% {
            transform: translateY(0) rotate(0deg);
        }
        100% {
            transform: translateY(100vh) rotate(360deg);
        }
    }
    </style>

</head>

<header>
    <h1>Salvation Army Girls Hostel - Guest Dashboard</h1>
    <div class="user-info">
        <p>Welcome, <?php echo $guest_name; ?>!</p>
        <br><a href="guest_logout.php">Logout</a>
    </div>
</header>

<body>
    <h1></h1>

    <!-- Room Information Section -->
    <section>
        <h2>Room Information</h2>
        
        <!-- View Room Vacancy -->
        <div>
            <h3>View Room Vacancy</h3><br>
            <!-- Display available rooms -->
            <a href="view_room_vacancy.php">See Available Rooms</a>
        </div>

        <!-- Apply for Room Vacancy -->
        <div>
            <h3>Apply for Room Vacancy</h3><br>
            <a href="register.php">Submit Room Application</a>
        </div>

        <!-- Rules for Residents -->
        <div>
            <h3>Rules and Regulations for Residents</h3><br>
            <a href="view_resident_rules.php">Read Resident Rules</a>
        </div>
    </section>

    <!-- Job Opportunities Section -->
    <section>
        <h2>Job Opportunities</h2><br>
        
        <!-- View Job Vacancy -->
        <div>
            <h3>View Job Vacancies</h3><br>
            <a href="employee_vacancies.php">See Open Positions</a>
        </div>

        <!-- Apply for Job -->
        <div>
            <h3>Apply for Job</h3><br>
            <a href="apply_for_job.php">Submit Job Application</a>
        </div>

        <!-- Rules for Employees -->
        <div>
            <h3>Rules and Regulations for Employees</h3><br>
            <a href="view_employee_rules.php">Read Employee Rules</a>
        </div>
    </section>
    <script>
    // Christmas Snow Animation Script
    function createSnowflake() {
        // Check if current month is December
        const currentDate = new Date();
        if (currentDate.getMonth() !== 11) { // 11 represents December (0-indexed)
            return; // Do not create snowflakes if not December
        }

        const snowflake = document.createElement('div');
        snowflake.classList.add('snowflake');
        snowflake.innerHTML = '❄️';
        
        // Randomize snowflake properties
        const size = Math.random() * 10 + 5; // 5-15px
        snowflake.style.fontSize = `${size}px`;
        snowflake.style.left = `${Math.random() * 100}%`;
        snowflake.style.animationDuration = `${Math.random() * 10 + 5}s`; // 5-15s
        snowflake.style.opacity = Math.random();
        
        document.body.appendChild(snowflake);

        // Remove snowflake after animation
        setTimeout(() => {
            snowflake.remove();
        }, 15000);
    }

    // Create snowflakes periodically only in December
    function startSnowfall() {
        const currentDate = new Date();
        if (currentDate.getMonth() === 11) { // Check if it's December
            setInterval(createSnowflake, 300); // Create a snowflake every 300ms
        }
    }

    // Start snowfall when page loads
    window.addEventListener('load', startSnowfall);
</script>
    
</body>
</html>
