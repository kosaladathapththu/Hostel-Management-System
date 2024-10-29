<?php
include 'db_connect.php'; // Include database connection

// Fetch booking ID from URL
$bookingId = $_GET['id'];

// Fetch the current booking details
$bookingQuery = "SELECT * FROM Bookings WHERE booking_id = $bookingId";
$bookingResult = $conn->query($bookingQuery);
$booking = $bookingResult->fetch_assoc();

// Fetch residents to populate dropdown
$residentsQuery = "SELECT id, name FROM Residents";
$residentsResult = $conn->query($residentsQuery);

// Fetch rooms
$roomsQuery = "SELECT room_id, room_number FROM Rooms";
$roomsResult = $conn->query($roomsQuery);

$notification = ""; // Variable to hold notification message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated form data
    $residentId = $_POST['resident_id'];
    $roomId = $_POST['room_id'];
    $checkInDate = $_POST['check_in_date'];
    $checkOutDate = $_POST['check_out_date'];

    // Update booking in the database
    $updateQuery = "UPDATE Bookings SET resident_id = '$residentId', room_id = '$roomId', check_in_date = '$checkInDate', check_out_date = '$checkOutDate' WHERE booking_id = $bookingId";
    if ($conn->query($updateQuery) === TRUE) {
        $notification = "Booking updated successfully.";
    } else {
        $notification = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
    <link rel="stylesheet" href="edit_booking.css"> <!-- Link to your CSS file -->
    <style>
        .notification {
            display: none; /* Hidden by default */
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 5px;
            transition: opacity 0.5s ease; /* Transition effect */
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Booking</h1>
        <p>User: [Admin Name] <a href="logout.php">Logout</a></p>
    </header>

    <section>
    <h2>Edit Booking</h2>
    
    <?php if ($notification): ?>
        <div class="notification" id="notification">
            <?php echo $notification; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="resident_id">Resident:</label>
            <select name="resident_id" required>
                <?php while($row = $residentsResult->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $booking['resident_id']) echo 'selected'; ?>><?php echo $row['name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="room_id">Room:</label>
            <select name="room_id" required>
                <?php while($row = $roomsResult->fetch_assoc()): ?>
                    <option value="<?php echo $row['room_id']; ?>" <?php if($row['room_id'] == $booking['room_id']) echo 'selected'; ?>><?php echo $row['room_number']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="check_in_date">Check-in Date:</label>
            <input type="date" name="check_in_date" value="<?php echo $booking['check_in_date']; ?>" required>
        </div>
        <div class="form-group">
            <label for="check_out_date">Check-out Date:</label>
            <input type="date" name="check_out_date" value="<?php echo $booking['check_out_date']; ?>" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Update Booking" class="submit-btn">
        </div>
    </form>
    <a href="bookings.php" class="back-btn">Back to Bookings List</a>
    <a href="dashboard.php" class="dashboard-btn">Dashboard</a> <!-- Dashboard Button -->
</section>


    <script>
        // Show the notification if it exists
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.display = 'block'; // Show the notification
            setTimeout(() => {
                notification.style.opacity = '0'; // Fade out
                setTimeout(() => notification.style.display = 'none', 500); // Hide after fade out
            }, 2000); // Show for 2 seconds
        }
    </script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
