<?php
include 'db_connect.php'; // Include your database connection file

// Query to fetch available rooms
$query = "SELECT room_number, capacity, status FROM Rooms WHERE status = 'available'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Rooms</title>
    <link rel="stylesheet" href="view_available_rooms.css">
</head>

<header>
        <h1>Salvation Army Girls Hostel </h1>
        <p> Resident: <a href="logout.php" class="logout-button">Logout</a></p>
    </header>

<body>
    <h2>Available Rooms for Booking</h2>
    <table>
        <thead>
            <tr>
                <th>Room Number</th>
                <th>Capacity</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td data-label="Room Number"><?php echo htmlspecialchars($row['room_number']); ?></td>
                        <td data-label="Capacity"><?php echo htmlspecialchars($row['capacity']); ?></td>
                        <td data-label="Status"><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No rooms available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
