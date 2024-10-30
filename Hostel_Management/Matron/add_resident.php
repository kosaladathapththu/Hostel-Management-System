<?php
include 'db_connect.php'; // Include database connection
$successMessage = '';
$errorMessage = '';

// Fetch available rooms for dropdown
$roomsQuery = "SELECT r.room_id, r.room_number, r.capacity, 
               (SELECT COUNT(*) FROM residents WHERE residents.room_id = r.room_id) as current_residents 
               FROM rooms r
               HAVING current_residents < r.capacity";
$roomsResult = $conn->query($roomsQuery);

// Handle form submission (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'];
    $national_id = $_POST['national_id'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $room_id = $_POST['room_id'];
    $status = $_POST['status'];

    // Check current capacity for selected room
    $capacityCheckQuery = "SELECT capacity, 
                           (SELECT COUNT(*) FROM residents WHERE room_id = $room_id) as current_residents 
                           FROM rooms WHERE room_id = $room_id";
    $capacityCheckResult = $conn->query($capacityCheckQuery);
    $roomData = $capacityCheckResult->fetch_assoc();

    if ($roomData['current_residents'] < $roomData['capacity']) {
        // Insert new resident into the database
        $insertQuery = "INSERT INTO residents (name, national_id, age, email, phone, room_id, status, created_at) 
                        VALUES ('$name', '$national_id', '$age', '$email', '$phone', '$room_id', '$status', NOW())";

        if ($conn->query($insertQuery) === TRUE) {
            $successMessage = "Resident added successfully.";
        } else {
            echo "Error: " . $insertQuery . "<br>" . $conn->error;
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
    <title>Add Resident</title>
    <link rel="stylesheet" href="stylesaddresident.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - Add Resident</h1>
        <div class="user-info">
            <p>Admin: [Admin Name]</p>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <section>
        <h2>Add New Resident</h2>

        <form action="add_resident.php" method="POST">
            <!-- Name Field -->
            <label for="name">Name:</label>
            <input type="text" name="name" required>

            <!-- National ID Field -->
            <label for="national_id">National ID:</label>
            <input type="text" name="national_id" required>

            <!-- Age Field -->
            <label for="age">Age:</label>
            <input type="number" name="age" required>

            <!-- Email Field -->
            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <!-- Phone Field -->
            <label for="phone">Phone:</label>
            <input type="text" name="phone" required>

            <!-- Room ID Field -->
            <label for="room_id">Room Number:</label>
            <select id="room_id" name="room_id" required>
                <option value="">Select a room</option>
                <?php while ($room = $roomsResult->fetch_assoc()): ?>
                    <option value="<?php echo $room['room_id']; ?>">
                        <?php echo $room['room_number']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <!-- Status Field -->
            <label for="status">Status:</label>
            <select name="status" required>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>

            
            <button type="submit">Add Resident</button>
        </form>

        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <div class="button-container">
            <a href="residents.php" class="back-btn">Back to Residents List</a>
            <a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a>
        </div>

        <!-- Toast Notification -->
        <div id="toast"><?php echo $successMessage; ?></div>
    </section>

    <script>
        // Show success message if resident is added
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
