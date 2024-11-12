<?php
include 'db_connect.php'; // Include database connection

$message = ''; // Variable for notification message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $roomNumber = $_POST['room_number'];
    $capacity = $_POST['capacity'];
    $status = $_POST['status'];

    // Insert room into the database
    $insertQuery = "INSERT INTO Rooms (room_number, capacity, status) VALUES ('$roomNumber', '$capacity', '$status')";
    if ($conn->query($insertQuery) === TRUE) {
        $message = 'Room added successfully.'; // Set the success message
    } else {
        $message = 'Error: ' . $conn->error; // Set the error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Room</title>
    <link rel="stylesheet" href="roomsstyles.css"> <!-- Link to your CSS file -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> <!-- Add Roboto font -->
    <script>
        function hideNotification() {
            setTimeout(function() {
                document.getElementById('notification').style.display = 'none';
            }, 3000); // 3000 milliseconds = 3 seconds
        }
    </script>
</head>
<body>
<header>
    <h1>Salvation Army Girls Hostel - Add Room</h1>
    <p>User: [Admin Name] 
    <a href="logout.php" class="logout-btn">Logout</a></p>
</header>

<section>
    <h2>Add Room</h2>
    <div id="notification" class="notification" style="display: <?php echo $message ? 'block' : 'none'; ?>;">
        <?php echo htmlspecialchars($message); ?>
    </div> <!-- Notification div -->
    
    <form method="POST" action="">
        <label for="room_number">Room Number:</label>
        <input type="text" name="room_number" required>
        <br>
        <label for="capacity">Capacity:</label>
        <input type="number" name="capacity" required>
        <br>
        <label for="status">Status:</label>
        <select name="status">
            <option value="available">Available</option>
            <option value="unavailable">Unavailable</option>
            <option value="Maintanance">Maintanance</option>
        </select>
        <br>
        <input type="submit" value="Add Room">
    </form>
    <a href="rooml.php">Back to Rooms List</a>
</section>

<script>
    // Hide the notification after a few seconds
    <?php if ($message) { ?>
        hideNotification();
    <?php } ?>
</script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
