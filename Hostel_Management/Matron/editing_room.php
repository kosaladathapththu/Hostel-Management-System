<?php
include 'db_connect.php'; // Include database connection

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $roomId = $_GET['id']; // Fetch room ID from the URL

    // Fetch room details
    $roomQuery = "SELECT * FROM Rooms WHERE room_id = $roomId";
    $roomResult = $conn->query($roomQuery);

    if ($roomResult && $roomResult->num_rows > 0) {
        $room = $roomResult->fetch_assoc();
    } else {
        die("Room not found."); // Handle the case where the room ID doesn't exist
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get updated form data
        $roomNumber = $_POST['room_number'];
        $capacity = $_POST['capacity'];
        $status = $_POST['status'];

        // Update room in the database
        $updateQuery = "UPDATE Rooms SET room_number = '$roomNumber', capacity = '$capacity', status = '$status' WHERE room_id = $roomId";
        if ($conn->query($updateQuery) === TRUE) {
            $notification = "Room updated successfully.";
        } else {
            $notification = "Error: " . $updateQuery . "<br>" . $conn->error;
        }
    }
} else {
    die("Invalid room ID."); // Handle the case where the 'id' parameter is missing or invalid
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Room</title>
    <link rel="stylesheet" href="roomsstyles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - Edit Room</h1>
        <p>User: [Admin Name] 
        <a href="logout.php" class="logout-btn">Logout</a></p>
    </header>

    <section>
        <h2>Edit Room</h2>
        <?php if (isset($notification)) : ?>
            <div class="notification"><?php echo $notification; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="room_number">Room Number:</label>
            <input type="text" name="room_number" value="<?php echo isset($room) ? htmlspecialchars($room['room_number']) : ''; ?>" required>
            <br>
            <label for="capacity">Capacity:</label>
            <input type="number" name="capacity" value="<?php echo isset($room) ? htmlspecialchars($room['capacity']) : ''; ?>" required>
            <br>
            <label for="status">Status:</label>
            <select name="status">
                <option value="available" <?php echo (isset($room) && $room['status'] == 'available') ? 'selected' : ''; ?>>Available</option>
                <option value="unavailable" <?php echo (isset($room) && $room['status'] == 'unavailable') ? 'selected' : ''; ?>>Unavailable</option>
            </select>
            <br>
            <input type="submit" value="Update Room">
        </form>
        
        <!-- Navigation Buttons -->
        <div class="nav-buttons">
    <a href="dashboard.php" class="nav-button">Dashboard</a>
    <a href="rooml.php" class="nav-button">Back to Rooms List</a>
</div>

    </section>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
