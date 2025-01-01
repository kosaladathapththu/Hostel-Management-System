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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Rules - Salvation Army Girls Hostel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="guest_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="employee_rules.css">
    <style>
        
    </style>
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
                        <a href="view_romm_vacancy.php">
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
        <h1>Employee Rules and Regulations</h1>
        <section class="rules">
            <ol>
                <li data-emoji="⏰">Employees must clock in and out on time.</li>
                <li data-emoji="👔">Always maintain a professional attitude and appearance.</li>
                <li data-emoji="🔒">Confidentiality regarding residents' personal information is a priority.</li>
                <li data-emoji="🛡️">Employees must comply with the hostel's safety guidelines at all times.</li>
                <li data-emoji="🖥️">No personal use of hostel resources during working hours.</li>
                <li data-emoji="🛠️">Employees are responsible for reporting any maintenance issues immediately.</li>
                <li data-emoji="📞">All requests from residents should be handled promptly and respectfully.</li>
                <li data-emoji="🧹">Employees should maintain a clean and organized work environment.</li>
                <li data-emoji="🚑">Any accidents or injuries must be reported immediately.</li>
                <li data-emoji="🚭">Smoking is prohibited within the building; designated areas are available outside.</li>
                <li data-emoji="🚷">No personal visitors are allowed during working hours.</li>
                <li data-emoji="📝">Employees must attend any mandatory meetings or training sessions.</li>
                <li data-emoji="📱">All social media activity should reflect the values of the hostel.</li>
                <li data-emoji="🤝">Employees should demonstrate teamwork and cooperation with colleagues.</li>
                <li data-emoji="🌸">Employee conduct should promote a peaceful and respectful environment.</li>
                <li data-emoji="👚">Employees must adhere to the hostel's dress code policy.</li>
                <li data-emoji="📣">Promptly address any complaints from residents with professionalism.</li>
                <li data-emoji="🚫">Any form of harassment or discrimination will not be tolerated.</li>
                <li data-emoji="🚔">Employees must report any suspicious activity or security concerns immediately.</li>
            </ol>
        </section>
        <div class="footer">
            Salvation Army Girls Hostel | Last Updated: December 2024
        </div>
    </div>
</body>
</html>