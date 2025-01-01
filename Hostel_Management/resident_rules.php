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
    <title>Resident Rules - Salvation Army Girls Hostel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="guest_dashboard.css">
    <link rel="stylesheet" href="resident_rules.css">
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
        <center><h1>Resident Rules and Regulations</h1>
        <section class="rules">
            <ol>
                <li data-emoji="📝">Residents must sign in and out when leaving the premises.</li>
                <li data-emoji="🚫📢">Quiet hours are enforced from 10 PM to 6 AM.</li>
                <li data-emoji="🚶‍♀️🚫">No visitors are allowed in the rooms without prior approval.</li>
                <li data-emoji="🧳">Personal items should be stored neatly in designated areas.</li>
                <li data-emoji="🎧">Noise should be kept to a minimum to respect others.</li>
                <li data-emoji="🚭🍷">No smoking or alcohol consumption within the premises.</li>
                <li data-emoji="🍽️">All meals must be consumed in the designated dining area.</li>
                <li data-emoji="💡🚪">Lights must be turned off when leaving the room.</li>
                <li data-emoji="⚒️">All damages or repairs should be reported immediately.</li>
                <li data-emoji="⏰">Residents must follow the curfew rules set by the matron.</li>
                <li data-emoji="📱💻">Laptops, phones, and other electronic devices should not disrupt communal areas.</li>
                <li data-emoji="🐾🚫">No pets allowed inside the hostel rooms.</li>
                <li data-emoji="🧺">All personal laundry must be done in the designated laundry area.</li>
                <li data-emoji="🤝">Respect for fellow residents and their privacy is mandatory.</li>
                <li data-emoji="🧼">Keep your room clean and free of pests.</li>
                <li data-emoji="🏠">Respect hostel property and treat it with care.</li>
                <li data-emoji="🚬🌳">Smoking areas are designated outside the building.</li>
                <li data-emoji="🩺">Reporting any health or safety concerns to the matron is required.</li>
                <li data-emoji="👊🚫">No fighting or physical altercations are allowed.</li>
                <li data-emoji="👜">Residents are responsible for their own belongings; the hostel is not liable for lost items.</li>
            </ol>
        </section></center>
    </div>
</body>
</html>