<?php
include 'db_connect.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $event_place = mysqli_real_escape_string($conn, $_POST['event_place']);
    $organizer = mysqli_real_escape_string($conn, $_POST['organizer']);

    // Insert new event into the database
    $insertQuery = "INSERT INTO events (title, description, start_date, end_date, start_time, event_place, organizer) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sssssss", $title, $description, $start_date, $end_date, $start_time, $event_place, $organizer);

    if ($stmt->execute()) {
        echo "<script>alert('Event added successfully.'); window.location.href='view_calendar.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close(); // Close the prepared statement
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Event</title>
    <link rel="stylesheet" href="add_event.css">
</head>
<body>
    <header>
        <h1>Add New Event</h1>
    </header>

    <form method="POST" action="">
        <label for="title">Event Title:</label>
        <input type="text" name="title" required>
        <br>
        <label for="description">Description:</label>
        <textarea name="description"></textarea>
        <br>
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" required>
        <br>
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" required>
        <br>
        <label for="start_time">Start Time:</label>
        <input type="time" name="start_time" required>
        <br>
        <label for="event_place">Event Place:</label>
        <input type="text" name="event_place" required>
        <br>
        <label for="organizer">Organizer:</label>
        <input type="text" name="organizer" required>
        <br>
        <input type="submit" value="Add Event">
        <a href="dashboard.php" class="dashboard-button">Dashboard</a>
        <a href="view_calendar.php">Back to Calendar</a>
    </form>
</body>
</html>
