<?php
include 'db_connect.php'; // Include database connection

// Check if the event ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $eventId = intval($_GET['id']);

    // Fetch event details
    $eventQuery = "SELECT * FROM Events WHERE event_id = ?";
    $stmt = $conn->prepare($eventQuery);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $eventResult = $stmt->get_result();

    if ($eventResult->num_rows > 0) {
        $event = $eventResult->fetch_assoc(); // Fetch event data
    } else {
        echo "<script>alert('Event not found.'); window.location.href='view_calendar.php';</script>";
        exit();
    }
    $stmt->close();
} else {
    echo "<script>alert('Invalid event ID.'); window.location.href='view_calendar.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);

    // Update event in the database
    $updateQuery = "UPDATE Events SET title = ?, description = ?, start_date = ?, end_date = ? WHERE event_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssssi", $title, $description, $start_date, $end_date, $eventId);

    if ($stmt->execute()) {
        echo "<script>alert('Event updated successfully.'); window.location.href='view_calendar.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link rel="stylesheet" href="edit_event.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Edit Event</h1>
    </header>

    <form method="POST" action="" class="event-form">
        <label for="title">Event Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
        
        <label for="description">Description:</label>
        <textarea name="description"><?php echo htmlspecialchars($event['description']); ?>"></textarea>
        
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" value="<?php echo htmlspecialchars($event['start_date']); ?>" required>
        
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" value="<?php echo htmlspecialchars($event['end_date']); ?>" required>
        
        <input type="submit" value="Update Event" class="btn">

        <!-- Delete Button -->
        <a href="delete_event.php?id=<?php echo $eventId; ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this event?')">Delete Event</a>
        <a href="dashboard.php" class="dashboard-button">Dashboard</a> 
    </form>

    <a href="view_calendar.php" class="back-link">Back to Calendar</a>
</body>
</html>
 



