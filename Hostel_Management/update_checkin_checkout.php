<?php
include 'db_connect.php';
session_start();

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
if ($residentStmt === false) {
    die('MySQL prepare error: ' . $conn->error);  // Check for errors
}
$residentStmt->bind_param("i", $resident_id);
$residentStmt->execute();
$residentResult = $residentStmt->get_result();
$residentData = $residentResult->fetch_assoc(); // Fetch data correctly

// Fetch current check-in and check-out details for the resident
$query = "SELECT check_in_date, check_out_date FROM `resident_checking-checkouts` WHERE resident_id = ? AND status = 'approved'";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);  // Check for errors
}
$stmt->bind_param("i", $resident_id);
$stmt->execute();
$result = $stmt->get_result(); // Get result from the prepared statement
$booking = $result->fetch_assoc(); // Fetch the result

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Check-in/Check-out</title>
    <link rel="stylesheet" href="update_checkin_checkout.css">
    <link rel="stylesheet" href="view_meal_plans.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Resident Panel</h2>
        <ul>
            <li class="active"><a href="resident_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="edit_profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="resident_view_meal_plans.php"><i class="fas fa-utensils"></i> Meals</a></li>
            <li><a href="update_checkin_checkout.php"><i class="fas fa-calendar-check"></i> Check-in/out</a></li>
            <li><a href="Re_view_calendar.php"><i class="fas fa-calendar"></i> Events</a></li>
            <li><a href="transaction.php"><i class="fa fa-credit-card"></i> Monthly Fee</a></li>
            <li><a href="#support"><i class="fas fa-headset"></i> Support</a></li>
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
            <center><b><h2 style="text-align:left;color:black">Salvation Army Girls Hostel</h2></b></center>
            <h4 style="text-align:left;color:black">Welcome, <?php echo htmlspecialchars($residentData['username']); ?></h4>
        </header>

        <section class="meal-plans-list">
            <div class="breadcrumbs">
                <span class="breadcrumb-separator">|</span>
                <a href="resident_dashboard.php" class="breadcrumb-item"><i class="fas fa-home"></i> Resident Dashboard</a>
            </div>

            <center>
                <h2>Update Check-in/Check-out Dates</h2><br>
                <p>Current Check-out Date: <?php echo htmlspecialchars($booking['check_out_date'] ?? 'Not Available'); ?></p><br>
                <p>Current Check-in Date: <?php echo htmlspecialchars($booking['check_in_date'] ?? 'Not Available'); ?></p><br>

                <table>
                    <tr>
                        <td>
                            <form method="POST" action="request_checkin_checkout_update.php" style="margin-left:200px;">
                                <label for="new_checkout">New Check-out Date:</label>
                                <input type="date" name="new_checkout" required>
                                <label for="new_checkin">New Check-in Date:</label>
                                <input type="date" name="new_checkin" required>
                                <button type="submit">Request Update</button>
                            </form>
                        </td>
                        <td>
                            <center>
                                <img src="resident.png" alt="supplierimage" style="width: 400px; height: auto; box-shadow: 10px 10px 8px 10px rgba(0, 0, 0, 0.2); margin-top: 10px; border-radius: 100px;">
                            </center>
                        </td>
                    </tr>
                </table>
            </center>
        </section>
    </div>
</body>
</html>
