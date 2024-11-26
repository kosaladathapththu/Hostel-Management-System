<?php
include 'db_connect.php'; // Include database connection

$successMessage = '';
$errorMessage = '';

// Check if resident ID is passed and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $residentId = intval($_GET['id']);

    // Fetch resident details
    $residentQuery = "SELECT * FROM residents WHERE id = ?";
    $stmt = $conn->prepare($residentQuery);
    $stmt->bind_param("i", $residentId);
    $stmt->execute();
    $residentResult = $stmt->get_result();

    // Check if resident exists
    if ($residentResult && $residentResult->num_rows > 0) {
        $resident = $residentResult->fetch_assoc();
    } else {
        die("Resident not found.");
    }

    // Fetch available rooms
    $roomsQuery = "
        SELECT r.room_id, r.room_number, r.capacity, 
        (SELECT COUNT(*) FROM residents WHERE residents.resident_room_no = r.room_id) as current_residents 
        FROM rooms r
        HAVING current_residents < r.capacity OR r.room_id = ?";
    $stmt = $conn->prepare($roomsQuery);
    $stmt->bind_param("i", $resident['resident_room_no']);
    $stmt->execute();
    $roomsResult = $stmt->get_result();
} else {
    echo "Invalid resident ID.";
    exit;
}

// Handle form submission (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated form data
    $resident_name = $_POST['resident_name'];
    $resident_id = $_POST['resident_id'];
    $resident_DOB = $_POST['resident_DOB'];
    $email = $_POST['email'];
    $resident_contact = $_POST['resident_contact'];
    $resident_room_no = $_POST['resident_room_no'];
    $status = $_POST['status'];

    // Check if the room has enough capacity before updating
    $capacityCheckQuery = "
        SELECT capacity, 
        (SELECT COUNT(*) FROM residents WHERE resident_room_no = ?) as current_residents 
        FROM rooms WHERE room_id = ?";
    $stmt = $conn->prepare($capacityCheckQuery);
    $stmt->bind_param("ii", $resident_room_no, $resident_room_no);
    $stmt->execute();
    $capacityCheckResult = $stmt->get_result();
    $roomData = $capacityCheckResult->fetch_assoc();

    if ($roomData['current_residents'] < $roomData['capacity'] || $resident_room_no == $resident['resident_room_no']) {
        // Update resident in the database
        $updateQuery = "
            UPDATE residents SET 
                resident_name = ?, 
                resident_id = ?,  
                resident_DOB = ?,  
                email = ?, 
                resident_contact = ?, 
                resident_room_no = ?, 
                status = ? 
            WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param(
            "ssssssii", 
            $resident_name, 
            $resident_id, 
            $resident_DOB, 
            $email, 
            $resident_contact, 
            $resident_room_no, 
            $status, 
            $residentId
        );

        if ($stmt->execute()) {
            $successMessage = "Resident updated successfully.";
        } else {
            $errorMessage = "Error updating resident: " . $stmt->error;
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
            <label for="resident_name">Name:</label>
            <input type="text" name="resident_name" value="<?php echo htmlspecialchars($resident['resident_name']); ?>" required>

            <!-- National ID Field -->
            <label for="resident_id">Resident ID:</label>
            <input type="text" name="resident_id" value="<?php echo htmlspecialchars($resident['resident_id']); ?>" required>

            <!-- Date of Birth Field -->
            <label for="resident_DOB">Date of Birth:</label>
            <input type="date" name="resident_DOB" value="<?php echo htmlspecialchars($resident['resident_DOB']); ?>" required>

            <!-- Email Field -->
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($resident['email']); ?>" required>

            <!-- Phone Field -->
            <label for="resident_contact">Phone:</label>
            <input type="text" name="resident_contact" value="<?php echo htmlspecialchars($resident['resident_contact']); ?>" required>

            <!-- Room Field -->
            <label for="resident_room_no">Room Number:</label>
            <select id="resident_room_no" name="resident_room_no" required>
                <option value="">Select a room</option>
                <?php while ($room = $roomsResult->fetch_assoc()): ?>
                    <option value="<?php echo $room['room_id']; ?>" <?php echo ($resident['resident_room_no'] == $room['room_id']) ? 'selected' : ''; ?>>
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
