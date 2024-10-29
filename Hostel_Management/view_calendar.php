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

    <!-- Add Dashboard and Add Event buttons -->
    <div class="button-container">
        <a href="dashboard.php" class="dashboard-button">Go to Dashboard</a>
        <a href="add_event.php" class="add-event-button">Add Event</a>
        <a href="generate_event_report.php?type=annually">Generate Annual Event Report</a>
        <a href="generate_event_report.php?type=monthly&year=2024">Generate Monthly Event Report for 2024</a>

    </div>

    <div id="calendar"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?php echo json_encode($eventsArray); ?>, // Load events from PHP
                editable: true,
                selectable: true,
                eventClick: function(info) {
                    // Ask if the user wants to edit or delete the event
                    var action = confirm('Would you like to edit or delete the event "' + info.event.title + '"? Press OK to edit or Cancel to delete.');
                    
                    if (action) {
                        // Redirect to edit_event.php with the event ID
                        window.location.href = 'edit_event.php?id=' + info.event.id;
                    } else {
                        // Redirect to delete_event.php with the event ID
                        if (confirm('Are you sure you want to delete this event?')) {
                            window.location.href = 'delete_event.php?id=' + info.event.id;
                        }
                    }
                }
            });

            calendar.render();
        });
    </script>
</body>
</html>
