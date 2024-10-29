<?php
include 'db_connect.php';
session_start();

$resident_id = $_SESSION['resident_id'];

// Fetch current check-in and check-out details for the resident
$query = "SELECT check_in_date, check_out_date FROM bookings WHERE resident_id = ? AND status = 'approved'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $resident_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Check-in/Check-out</title>
    <link rel="stylesheet" href="update_checkin_checkout.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<header>
    <h1>Salvation Army Girls Hostel - Checking/Checkouts</h1>
    <div class="user-info">
        
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</header> 

<body><center>
    <h2>Update Check-in/Check-out Dates</h2>
    <p>Current Check-in Date: <?php echo $booking['check_in_date'] ?? 'Not Available'; ?></p>
    <p>Current Check-out Date: <?php echo $booking['check_out_date'] ?? 'Not Available'; ?></p>

    <form method="POST" action="request_checkin_checkout_update.php">
        <label for="new_checkin">New Check-in Date:</label>
        <input type="date" name="new_checkin" required>

        <label for="new_checkout">New Check-out Date:</label>
        <input type="date" name="new_checkout" required>

        <button type="submit">Request Update</button>
       
    </form></center>
    <div>
    <a href="resident_dashboard.php" class="dashboard-button">Dashboard</a>
    </div>
</body>
</html>
