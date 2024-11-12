<?php
include 'db_connect.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);

    // Insert new event into the database
    $insertQuery = "INSERT INTO Events (title, description, start_date, end_date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssss", $title, $description, $start_date, $end_date);

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
        <input type="submit" value="Add Event">
        <a href="dashboard.php" class="dashboard-button">Dashboard</a>
        <a href="view_calendar.php">Back to Calendar</a>
    </form>
    
    
</body>
</html>
