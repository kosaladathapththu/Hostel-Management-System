<?php
include 'db_connect.php'; // Include the database connection

$message = ""; // Initialize message variable
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

// Check if resident_id exists in the residents table before proceeding with feedback insertion
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resident_id = $_POST['resident_id'];
    $meal_id = $_POST['meal_id'];
    $comment = $_POST['comment'];
    $rating = $_POST['rating'];

    // Check if resident_id exists in residents table
    $checkResidentQuery = "SELECT id FROM residents WHERE id = ?";
    $checkResidentStmt = $conn->prepare($checkResidentQuery);
    $checkResidentStmt->bind_param("i", $resident_id);
    $checkResidentStmt->execute();
    $checkResidentResult = $checkResidentStmt->get_result();

    if ($checkResidentResult->num_rows > 0) {
        // Resident exists, proceed with inserting feedback
        $sql = "INSERT INTO meal_feedback (resident_id, meal_id, comment, rating) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisi", $resident_id, $meal_id, $comment, $rating);

        if ($stmt->execute()) {
            $message = "Feedback submitted successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    } else {
        $message = "Resident ID is not valid!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Feedback</title>
    <link rel="stylesheet" href="submit_feedback.css">
    <link rel="stylesheet" href="view_meal_plans.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Resident Panel</h2>
        <ul>
            <li class="active"><a href="resident_dashboard.php"><i class="fas fa-home"></i>Dashboard</a></li>
            <li><a href="edit_profile.php"><i class="fas fa-user"></i>Profile</a></li>
            <li><a href="resident_view_meal_plans.php"><i class="fas fa-utensils"></i>Meals</a></li>
            <li><a href="update_checkin_checkout.php"><i class="fas fa-calendar-check"></i>Check-in/out</a></li>
            <li><a href="Re_view_calendar.php"><i class="fas fa-calendar"></i>Events</a></li>
            <li><a href="transaction.php"><i class="fa fa-credit-card"></i>Monthly Fee</a></li>
            <li><a href="#support"><i class="fas fa-headset"></i>Support</a></li>
        </ul>
        <button onclick="window.location.href='admin_edit_profile.php'" class="edit-btn"><i class="fas fa-user-edit"></i> Edit Profile</button>
        <button onclick="window.location.href='admin_logout.php'" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div class="header-left">
                <img src="The_Salvation_Army.png" alt="Logo" class="logo">
            </div>
            <center><b><h1 style="text-align: center; margin-left: 100px;">Salvation Army Girls Hostel</h1></b></center>
            <h4>Welcome,<?php echo htmlspecialchars($residentData['username']); ?></h4>
        </header>

        <section class="meal-plans-list">
            <h2 style="margin-top:10px; margin-left:10px;"> Submit Your Feedback</h2>
            <div class="breadcrumbs">
                <a href="resident_view_meal_plans.php" class="breadcrumb-item"><i class="fas fa-utensils"></i>Back to meal plan</a>
                <span class="breadcrumb-separator">|</span>
                <a href="resident_dashboard.php" class="breadcrumb-item"><i class="fas fa-home"></i> Resident Dashboard</a>
            </div>

            <!-- Success/Error Message Display -->
            <?php if (!empty($message)) : ?>
                <div class="notification" id="notification"><?php echo $message; ?></div>
            <?php endif; ?>

            <table>
                <tr>
                    <td>
                        <center>
                            <img src="food.png" alt="supplierimage" style="width: 400px; height: 400px; box-shadow: 5px 5px 8px 5px rgba(0, 0, 0, 0.2); margin-top: 10px; border-radius: 30px;">
                        </center>
                    </td>
                    <td>
                        <center>
                            <form method="POST" style="width: 500px; height: 500px; box-shadow: 10px 10px 8px 10px rgba(0, 0, 0, 0.2); margin-top: 10px; border-radius: 30px;">
                                <label>Meal Plan ID:</label>
                                <input type="number" name="meal_id" required><br>

                                <label>Resident ID:</label>
                                <input type="number" name="resident_id" required><br>

                                <label>Comment:</label>
                                <textarea name="comment" required></textarea><br>

                                <label>Rating (1-5):</label>
                                <input type="number" name="rating" min="1" max="5" required><br>

                                <button type="submit">Submit Feedback</button>
                            </form>
                        </center>
                    </td>
                </tr>
            </table>

            <script>
                // JavaScript to hide notification after 3 seconds
                window.onload = function() {
                    const notification = document.getElementById('notification');
                    if (notification) {
                        setTimeout(() => {
                            notification.style.display = 'none';
                        }, 3000);
                    }
                };
            </script>
        </body>
    </html>
