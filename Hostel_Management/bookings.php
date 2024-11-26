<?php
// Include database connection
include 'db_connect.php';

// Check database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// SQL query to fetch booking details
$query = "
SELECT b.booking_id, r.resident_name, ro.room_number, b.check_in_date, b.check_out_date, b.status, b.created_at
FROM Bookings b
JOIN Residents r ON b.resident_id = r.resident_id
JOIN Rooms ro ON b.room_id = ro.room_id
ORDER BY b.check_in_date DESC";

// Prepare the query
$stmt = $conn->prepare($query);

// Check if the statement is prepared successfully
if (!$stmt) {
    die("SQL error: " . $conn->error); // Detailed error if statement fails
}

// Execute the query
$stmt->execute();

// Fetch the results
$bookingsResult = $stmt->get_result();

// Check if there are any results
if (!$bookingsResult || $bookingsResult->num_rows == 0) {
    echo "No bookings found."; // Display a message if no bookings exist
} else {
    echo "Bookings found: " . $bookingsResult->num_rows; // Display number of bookings found for debugging
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
    <link rel="stylesheet" href="bookstylelcss.css">
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
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $bookingsResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['resident_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['check_in_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['check_out_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <a href="edit_booking.php?id=<?php echo htmlspecialchars($row['booking_id']); ?>" class="edit-btn">Edit</a>
                        <a href="delete_booking.php?booking_id=<?php echo htmlspecialchars($row['booking_id']); ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="nav-buttons">
            <a href="add_booking.php" class="add-btn">Add New Booking</a>
            <a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a>
            <a href="manage_checkin_checkout.php" class="dashboard-btn">Approve Check-ins</a>
        </div>
    </section>
</body>
</html>

<?php
// Close statement and connection
$stmt->close();
$conn->close();
?>

