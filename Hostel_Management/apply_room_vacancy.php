<?php
// Connect to the database
include 'db_connect.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $guest_name = $_POST['guest_name'];
    $guest_email = $_POST['guest_email'];
    $room_id = $_POST['room_id'];

    // Insert application into room_applications table
    $insert_query = "INSERT INTO room_applications (guest_name, guest_email, room_id) VALUES ('$guest_name', '$guest_email', '$room_id')";
    $insert_result = mysqli_query($conn, $insert_query);

    if ($insert_result) {
        echo "<p>Application submitted successfully! We will review your application soon.</p>";
    } else {
        echo "<p>Error: Could not submit application. Please try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apply for Room Vacancy</title>
</head>
<body>
    <h2>Apply for a Room Vacancy</h2>
    <form method="POST" action="apply_room_vacancy.php">
        <label for="guest_name">Name:</label>
        <input type="text" name="guest_name" id="guest_name" required><br><br>

        <label for="guest_email">Email:</label>
        <input type="email" name="guest_email" id="guest_email" required><br><br>

        <label for="room_id">Select Room:</label>
        <select name="room_id" id="room_id" required>
            <?php
            // Fetch available rooms from the database
            $rooms_query = "SELECT room_id, room_number, capacity FROM rooms WHERE status = 'available'";
            $rooms_result = mysqli_query($conn, $rooms_query);

            // Populate dropdown with available rooms
            while ($room = mysqli_fetch_assoc($rooms_result)) {
                echo "<option value='" . $room['room_id'] . "'>Room " . $room['room_number'] . " (Capacity: " . $room['capacity'] . ")</option>";
            }

                        // Update room status to pending
            $update_room_status = "UPDATE rooms SET status = 'pending' WHERE room_id = '$room_id'";
            mysqli_query($conn, $update_room_status);


            ?>
        </select><br><br>

        <input type="submit" value="Apply">
    </form>
</body>
</html>
