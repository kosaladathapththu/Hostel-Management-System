<?php
include 'db_connect.php'; // Include the database connection

session_start(); // Start session

// Check if resident is logged in; if not, redirect to login page
if (!isset($_SESSION['resident_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch resident_id from session
$resident_id = $_SESSION['resident_id'];

// Fetch resident data
$residentQuery = "SELECT username FROM residents WHERE id = ?";
$residentStmt = $conn->prepare($residentQuery);
$residentStmt->bind_param("i", $resident_id);
$residentStmt->execute();
$residentResult = $residentStmt->get_result();
$residentData = $residentResult->fetch_assoc();

// Fetch meal plans from the database
$sql = "SELECT meal_id, meal_name, description, date, created_by, created_at FROM meal_plans ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Meal Plans</title>
    <link rel="stylesheet" href="view_meal_plans.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Resident Panel</h2>
        
        <ul>
                    <li class="active">
                        <a href="resident_dashboard.php"><i class="fas fa-home"></i>Dashboard</a>
                    </li>
                    <li>
                        <a href="edit_profile.php"><i class="fas fa-user"></i>Profile</a>
                    </li>
                    <li>
                        <a href="resident_view_meal_plans.php"><i class="fas fa-utensils"></i>Meals</a>
                    </li>
                    <li>
                        <a href="update_checkin_checkout.php"><i class="fas fa-calendar-check"></i>Check-in/out</a>
                    </li>
                    <li>
                        <a href="Re_view_calendar.php"><i class="fas fa-calendar"></i>Events</a>
                    </li>
                    <li>
                        <a href="transaction.php"><i class="fa fa-credit-card"></i>Monthly Fee</a>
                    </li>
                    <li>
                        <a href="#support"><i class="fas fa-headset"></i>Support</a>
                    </li>
                </ul>
                <button onclick="window.location.href='edit_profile.php'" class="edit-btn"><i class="fas fa-user-edit"></i> Edit Profile</button>
                <button onclick="window.location.href='login.php'" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div class="header-left">
                <img src="The_Salvation_Army.png" alt="Logo" class="logo">
            </div>
            <center><b><h2 style="text-align:left;">Salvation Army Girls Hostel</h2></b></center>
            <div class="header-right">
            <h4>Welcome,<?php echo htmlspecialchars($residentData['username']); ?></h4>
</div>
        </header>

        <section class="meal-plans-list">
            <h2  style="margin-top:10px; margin-left:10px;">Available Meal Plans</h2>
            <div class="breadcrumbs">
            <a href="submit_feedback.php" class="breadcrumb-item">
        <i class="fas fa-comments"></i> Submit Feedback
    </a>
    <span class="breadcrumb-separator">|</span>
    <a href="resident_dashboard.php" class="breadcrumb-item">
        <i class="fas fa-home"></i> Resident Dashboard
    </a>

</div>
            <?php if ($result && $result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Meal ID</th>
                            <th>Meal Name</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Created By</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['meal_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['meal_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_by']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No meal plans available at the moment.</p>
            <?php endif; ?>


        </section>
    </div>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
