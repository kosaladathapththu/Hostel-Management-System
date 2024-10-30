<?php
include 'db_connect.php'; // Include database connection

// Fetch all rooms from the database
$roomsQuery = "SELECT * FROM Rooms";
$roomsResult = $conn->query($roomsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rooms List</title>
    <link rel="stylesheet" href="roomlstyle.css"> <!-- Link to your CSS file -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - Rooms List</h1>
        <div class="user-info">
            <p>Admin: [Admin Name]</p>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <section>
        <h2>Rooms List</h2>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room Number</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($room = $roomsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $room['room_id']; ?></td>
                        <td><?php echo $room['room_number']; ?></td>
                        <td><?php echo $room['capacity']; ?></td>
                        <td><?php echo $room['status']; ?></td>
                        <td><?php echo $room['created_at']; ?></td>
                        <td>
                            <a href="editing_room.php?id=<?php echo $room['room_id']; ?>" class="edit-btn">Edit</a>
                            <a href="delete_room.php?id=<?php echo $room['room_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this room?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="nav-buttons">
            <a href="adding_room.php" class="add-btn">Add New Room</a>
            <a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a>
        </div>

    </section>

     

    <script>
        // Show the toast notification if needed
        <?php if (!empty($successMessage)): ?>
            var toast = document.getElementById("toast");
            toast.className = "show";
            setTimeout(function(){ toast.className = toast.className.replace("show", ""); }, 3000);
        <?php endif; ?>
    </script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
