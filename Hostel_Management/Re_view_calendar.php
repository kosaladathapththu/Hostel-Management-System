<?php
include 'db_connect.php'; // Include database connection

// Fetch events from the database
$eventsQuery = "SELECT * FROM Events";
$eventsResult = $conn->query($eventsQuery);

$eventsArray = [];
while ($row = $eventsResult->fetch_assoc()) {
    $eventsArray[] = [
        'id' => $row['event_id'],
        'title' => $row['title'],
        'start' => $row['start_date'],
        'end' => $row['end_date'],
        'description' => $row['description']
    ];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Calendar</title>
    <link rel="stylesheet" href="view_calendar.css">
    <!-- Include FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - Event Calendar</h1>
        <p>User: [Matron Name] <a href="logout.php">Logout</a></p>
    </header>

    </div>

    <div id="calendar"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?php echo json_encode($eventsArray); ?>, 
                editable: true,
                selectable: true,
            })

            calendar.render();
        });
    </script>
</body>
</html>
