<?php
include 'db_connect.php'; // Include database connection

$successMessage = '';
$errorMessage = '';

// Check if resident ID is passed and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $residentId = intval($_GET['id']);

    // Fetch resident details
    $residentQuery = "SELECT * FROM residents WHERE id = $residentId";
    $residentResult = $conn->query($residentQuery);

    // Check if resident exists
    if ($residentResult && $residentResult->num_rows > 0) {
        $resident = $residentResult->fetch_assoc();
    } else {
        die("Resident not found.");
    }

    // Fetch available rooms
    $roomsQuery = "SELECT r.room_id, r.room_number, r.capacity, 
                  (SELECT COUNT(*) FROM residents WHERE residents.room_id = r.room_id) as current_residents 
                  FROM rooms r
                  HAVING current_residents < r.capacity OR r.room_id = " . intval($resident['room_id']);
    $roomsResult = $conn->query($roomsQuery);
} else {
    echo "Invalid resident ID.";
    exit;
}

// Handle form submission (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated form data
    $name = $_POST['name'];
    $national_id = $_POST['national_id']; 
    $age = $_POST['age']; 
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $room_id = $_POST['room_id'];
    $status = $_POST['status'];

    // Check if the room has enough capacity before updating
    $capacityCheckQuery = "SELECT capacity, 
                           (SELECT COUNT(*) FROM residents WHERE room_id = $room_id) as current_residents 
                           FROM rooms WHERE room_id = $room_id";
    $capacityCheckResult = $conn->query($capacityCheckQuery);
    $roomData = $capacityCheckResult->fetch_assoc();

    if ($roomData['current_residents'] < $roomData['capacity'] || $room_id == $resident['room_id']) {
        // Update resident in the database
        $updateQuery = "UPDATE residents SET 
            name = '$name', 
            national_id = '$national_id',  
            age = '$age',  
            email = '$email', 
            phone = '$phone', 
            room_id = '$room_id', 
            status = '$status' 
            WHERE id = $residentId";

        if ($conn->query($updateQuery) === TRUE) {
            $successMessage = "Resident updated successfully.";
        } else {
            echo "Error: " . $updateQuery . "<br>" . $conn->error;
        }
    } else {
        $errorMessage = "Error: The selected room is already at full capacity.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Resident</title>
    <link rel="stylesheet" href="styleseditresident.css"> <!-- Link to your CSS file -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - Edit Resident</h1>
        <div class="user-info">
            <p>Admin: [Admin Name]</p>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <section>
        <h2>Edit Resident</h2>

        <form action="edit_resident.php?id=<?php echo $residentId; ?>" method="POST">
            <!-- Name Field -->
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($resident['name']); ?>" required>

            <!-- National ID Field -->
            <label for="national_id">National ID:</label>
            <input type="text" name="national_id" value="<?php echo htmlspecialchars($resident['national_id']); ?>" required>

            <!-- Age Field -->
            <label for="age">Age:</label>
            <input type="number" name="age" value="<?php echo htmlspecialchars($resident['age']); ?>" required>

            <!-- Email Field -->
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($resident['email']); ?>" required>

            <!-- Phone Field -->
            <label for="phone">Phone:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($resident['phone']); ?>" required>

            <!-- Room ID Field -->            
            <label for="room_id">Room Number:</label>
            <select id="room_id" name="room_id" required>
                <option value="">Select a room</option>
                <?php while ($room = $roomsResult->fetch_assoc()): ?>
                    <option value="<?php echo $room['room_id']; ?>" <?php echo ($resident['room_id'] == $room['room_id']) ? 'selected' : ''; ?>>
                        <?php echo $room['room_number']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <!-- Status Field -->
            <label for="status">Status:</label>
            <select name="status" required>
                <option value="1" <?php echo ($resident['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                <option value="0" <?php echo ($resident['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
            </select>

            <!-- Submit Button -->
            <button type="submit">Update Resident</button>
        </form>

        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <div class="button-container">
            <a href="residents.php" class="back-btn">Back to Residents List</a>
            <a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a>
        </div>
    </section>

    <!-- Toast Notification -->
    <div id="toast"><?php echo $successMessage; ?></div>

    <script>
        // Show the toast notification if the resident was updated successfully
        <?php if (!empty($successMessage)): ?>
            var toast = document.getElementById("toast");
            toast.className = "show";
            setTimeout(function(){ toast.className = toast.className.replace("show", ""); }, 3000);
        <?php endif; ?>
    </script>
</body>
</html>

<?php
$conn->close(); 
?>
