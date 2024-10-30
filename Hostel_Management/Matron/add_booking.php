<?php
include 'db_connect.php'; // Include database connection

// Fetch residents to populate dropdown
$residentsQuery = "SELECT id, name FROM Residents";
$residentsResult = $conn->query($residentsQuery);

// Fetch available rooms
$roomsQuery = "SELECT room_id, room_number FROM Rooms WHERE status = 'available'";
$roomsResult = $conn->query($roomsQuery);

$notification = ''; // Variable to store notification

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $residentId = $_POST['resident_id'];
    $roomId = $_POST['room_id'];
    $checkInDate = $_POST['check_in_date'];
    $checkOutDate = $_POST['check_out_date'];

    // Insert booking into the database
    $insertQuery = "INSERT INTO Bookings (resident_id, room_id, check_in_date, check_out_date) 
                    VALUES ('$residentId', '$roomId', '$checkInDate', '$checkOutDate')";
                    
    if ($conn->query($insertQuery) === TRUE) {
        // Update room status to unavailable after successful booking
        $updateRoomQuery = "UPDATE Rooms SET status = 'unavailable' WHERE room_id = $roomId";
        $conn->query($updateRoomQuery);
        $notification = "<div class='notification success'>Booking added successfully.</div>";
    } else {
        $notification = "<div class='notification error'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Booking</title>
    <link rel="stylesheet" href="bookingstyl.css"> <!-- Link to your custom CSS -->
    <script>
        // JavaScript to remove the notification after 5 seconds
        window.onload = function() {
            setTimeout(function() {
                var notification = document.querySelector('.notification');
                if (notification) {
                    notification.style.display = 'none';
                }
            }, 5000); // 5000ms = 5 seconds
        };
    </script>
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - Add Booking</h1>
        <div class="user-info">
            <p>Admin: [Admin Name] <a href="logout.php" class="logout-btn">Logout</a></p>
        </div>
    </header>

    <?php echo $notification; ?> <!-- Display the notification here -->

    <section>
        <h2>Add Booking</h2>
        <form method="POST" action="">
            <label for="resident_id">Resident:</label>
            <select name="resident_id" required>
                <?php while($row = $residentsResult->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php endwhile; ?>
            </select>

            <label for="room_id">Room:</label>
            <select name="room_id" required>
                <?php while($row = $roomsResult->fetch_assoc()): ?>
                    <option value="<?php echo $row['room_id']; ?>"><?php echo $row['room_number']; ?></option>
                <?php endwhile; ?>
            </select>

            <label for="check_in_date">Check-in Date:</label>
            <input type="date" name="check_in_date" required>

            <label for="check_out_date">Check-out Date:</label>
            <input type="date" name="check_out_date" required>

            <input type="submit" value="Add Booking">
        </form>
        <div class="button-container">
            <a href="bookings.php" class="back-btn">Back to Bookings List</a>
            <a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a>
            <a href="manage_checkin_checkout.php" class="dashboard-btn">Aprove Checking</a>
            
        </div>
    </section>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
