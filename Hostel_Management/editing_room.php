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
    die("Invalid room ID."); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Room</title>
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

    <!-- Main Content -->
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
            <h2 style="margin-top:20px;">Edit Room</h2>
            <?php if (isset($notification)) : ?>
                <div class="notification"><?php echo $notification; ?></div>
            <?php endif; ?>
            <div class="breadcrumbs">
                <a href="rooml.php" class="breadcrumb-item">
                    <i class="fas fa-arrow-left"></i> Back to Rooms List
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="dashboard.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>

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

        </section>
    </div>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
