<?php
include 'db_connect.php'; // Include database connection

// Fetch all bookings
$bookingsQuery = "
SELECT b.booking_id, r.name as resident_name, ro.room_number, b.check_in_date, b.check_out_date 
FROM Bookings b
JOIN Residents r ON b.resident_id = r.id
JOIN Rooms ro ON b.room_id = ro.room_id
ORDER BY b.check_in_date DESC";
$bookingsResult = $conn->query($bookingsQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
    <link rel="stylesheet" href="bookstylelcss.css"> <!-- Link to your CSS file -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <h1>Salvation Army Girls Hostel - Bookings</h1>
        <div class="user-info">
            <p>Admin: [Admin Name]</p>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <section>
        <h2>Bookings List</h2>
        <table>
            <thead>
                <tr>
                    <th>Resident</th>
                    <th>Room Number</th>
                    <th>Check-in Date</th>
                    <th>Check-out Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $bookingsResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['resident_name']; ?></td>
                    <td><?php echo $row['room_number']; ?></td>
                    <td><?php echo $row['check_in_date']; ?></td>
                    <td><?php echo $row['check_out_date']; ?></td>
                    <td>
    <a href="edit_booking.php?id=<?php echo $row['booking_id']; ?>" class="edit-btn">Edit</a>
    <a href="delete_booking.php?booking_id=<?php echo $row['booking_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</a>
</td>

                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="nav-buttons">
            <a href="add_booking.php" class="add-btn">Add New Booking</a>
            <a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a>
            <a href="manage_checkin_checkout.php" class="dashboard-btn">Aprove Checking</a>
        </div>
    </section>
</body>

</html>

<?php
$conn->close(); // Close the database connection
?>
