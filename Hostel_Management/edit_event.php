<?php
session_start(); // Start session for authentication check

// Check if the user is logged in as a matron
if (!isset($_SESSION['matron_id'])) {
    header("Location: matron_auth.php"); // Redirect if not logged in
    exit();
}

include 'db_connect.php'; // Include database connection

// Fetch the matron's details (Extra safety check)
$matron_id = $_SESSION['matron_id'];
$matronQuery = "SELECT first_name FROM Matrons WHERE matron_id = ?";
$stmt = $conn->prepare($matronQuery);
$stmt->bind_param("i", $matron_id);
$stmt->execute();
$matronResult = $stmt->get_result();

if ($matronResult->num_rows === 0) {
    header("Location: matron_auth.php"); // Redirect if matron not found
    exit();
}

// Assign matron's first name
$matronData = $matronResult->fetch_assoc();
$matron_first_name = $matronData['first_name'];

// Check if the event ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $eventId = intval($_GET['id']);

    // Fetch event details
    $eventQuery = "SELECT * FROM events WHERE event_id = ?";
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
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $event_place = mysqli_real_escape_string($conn, $_POST['event_place']);
    $organizer = mysqli_real_escape_string($conn, $_POST['organizer']);

    // Update event in the database
    $updateQuery = "UPDATE events SET title = ?, description = ?, start_date = ?, end_date = ?, start_time = ?, event_place = ?, organizer = ? WHERE event_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssssssi", $title, $description, $start_date, $end_date, $start_time, $event_place, $organizer, $eventId);

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
    <link rel="stylesheet" href="stylesresident.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Matron Panel</h2>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="residents.php"><i class="fas fa-users"></i> Residents</a></li>
            <li><a href="bookings.php"><i class="fas fa-calendar-check"></i> Check-ins/Outs</a></li>
            <li><a href="rooml.php"><i class="fas fa-door-open"></i> Rooms</a></li>
            <li><a href="payments.php"><i class="fas fa-money-check-alt"></i> Payments</a></li>
            <li><a href="view_suppliers.php"><i class="fas fa-truck"></i> Suppliers</a></li>
            <li><a href="view_order.php"><i class="fas fa-receipt"></i> Orders</a></li>
            <li><a href="view_inventory.php"><i class="fas fa-boxes"></i> Inventory</a></li>
            <li><a href="view_calendar.php"><i class="fas fa-calendar"></i> Events</a></li>
            <li><a href="view_meal_plans.php"><i class="fas fa-utensils"></i> Meal Plans</a></li>
        </ul>
        <button onclick="window.location.href='matron_logout.php'" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>

    <div class="main-content">
        <header>
            <div class="header-left">
                <img src="The_Salvation_Army.png" alt="Logo" class="logo"> 
            </div>
            <center><b><h1>Salvation Army Girls Hostel</h1></b></center>
            <div class="header-right">
                <p>Welcome, <?php echo htmlspecialchars($matron_first_name); ?></p>
            </div>
        </header>

        <section>
            <h2 style="margin-top:20px;">Edit Event</h2>
            <div class="breadcrumbs">
                <a href="view_calendar.php" class="breadcrumb-item">
                    <i class="fas fa-calendar"></i> View Events
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="dashboard.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span class="breadcrumb-separator">|</span>
                <!-- Delete Button -->
                <a href="delete_event.php?id=<?php echo $eventId; ?>" class="breadcrumb-item" onclick="return confirm('Are you sure you want to delete this event?')"><i class="fas fa-trash"></i>Delete Event</a>

            </div>

            <form method="POST" action="" class="event-form">
                <label for="title">Event Title:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
                
                <label for="description">Description:</label>
                <textarea name="description"><?php echo htmlspecialchars($event['description']); ?></textarea>
                
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" value="<?php echo htmlspecialchars($event['start_date']); ?>" required>
                
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" value="<?php echo htmlspecialchars($event['end_date']); ?>" required>
                
                <label for="start_time">Start Time:</label>
                <input type="time" name="start_time" value="<?php echo htmlspecialchars($event['start_time']); ?>" required>

                <label for="event_place">Event Place:</label>
                <input type="text" name="event_place" value="<?php echo htmlspecialchars($event['event_place']); ?>" required>

                <label for="organizer">Organizer:</label>
                <input type="text" name="organizer" value="<?php echo htmlspecialchars($event['organizer']); ?>" required>
                
                <input type="submit" value="Update Event" class="btn">

                <!-- Delete Button -->
                <a href="delete_event.php?id=<?php echo $eventId; ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this event?')">Delete Event</a>

            </form>

        </section>
    </div>
</body>
</html>

<?php
$conn->close();
?>
