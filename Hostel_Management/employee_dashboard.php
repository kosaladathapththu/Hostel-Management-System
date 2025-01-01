<?php
session_start();
if (!isset($_SESSION['employee'])) {
    header("Location: employee_login.php");
    exit();
}
$employee = $_SESSION['employee'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard | Salvation Army Girls Hostel</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="employee_dashboard.css">

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
<body>
    <!-- Sidebar -->
    <div class="sidebar">
    <div class="logo-container">
        <img src="The_Salvation_Army.png" alt="Logo" class="logo" style="width: 60px; height: 60px;"> 

        <h2>Salvation Army<br>Girls Hostel</h2>
    </div>
        <ul class="nav-menu">
            <li><a href="employee_dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="employee_view_leave_status.php"><i class="fas fa-calendar-check"></i><span>Leave Status</span></a></li>
            <li><a href="employee_view_remaining_leave.php"><i class="fas fa-calendar-alt"></i><span>Remaining Leave</span></a></li>
            <li><a href="checkin.php"><i class="fas fa-sign-in-alt"></i><span>Check-in</span></a></li>
            <li><a href="update_ckeckout.php"><i class="fas fa-sign-out-alt"></i><span>Check-out</span></a></li>
            <li><a href="view_attendance.php"><i class="fas fa-clipboard-list"></i><span>Attendance</span></a></li>

            <li><a href="view_paysheet.php"><i class="fas fa-file-invoice-dollar"></i><span>Paysheet</span></a></li>
            <li><a href="employee_change_details.php"><i class="fas fa-user-edit"></i><span>Profile</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Welcome, <?php echo $employee['name']; ?></h1>
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <a href="employee_logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="metrics-grid">
            <div class="metric-box">
                <i class="fas fa-clock metric-icon"></i>
                <div class="metric-value">8.5 hrs</div>
                <div class="metric-label">Today's Working Hours</div>
            </div>
            <div class="metric-box">
                <i class="fas fa-calendar-check metric-icon"></i>
                <div class="metric-value">15 days</div>
                <div class="metric-label">Remaining Leave Balance</div>
            </div>
            <div class="metric-box">
                <i class="fas fa-chart-line metric-icon"></i>
                <div class="metric-value">92%</div>
                <div class="metric-label">Attendance Rate</div>
            </div>
            <div class="metric-box">
                <i class="fas fa-tasks metric-icon"></i>
                <div class="metric-value">5</div>
                <div class="metric-label">Pending Tasks</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <div class="action-card">
                <i class="fas fa-clock action-icon"></i>
                <h3 class="action-title">Quick Check-in</h3>
                <p>Mark your attendance</p>
            </div>
            <div class="action-card">
                <i class="fas fa-file-alt action-icon"></i>
                <h3 class="action-title">Apply Leave</h3>
                <p>Request time off</p>
            </div>
            <div class="action-card">
                <i class="fas fa-calendar action-icon"></i>
                <h3 class="action-title">Schedule</h3>
                <p>View your timetable</p>
            </div>
        </div>
    </div>

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