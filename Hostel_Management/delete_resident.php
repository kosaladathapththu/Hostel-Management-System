<?php
include 'db_connect.php'; // Include database connection

// Check if 'resident_id' is present in the URL
if (!isset($_GET['resident_id']) || empty($_GET['resident_id'])) {
    // Redirect back to the residents list or show an error message
    header("Location: residents.php");
    exit();
}

// Fetch resident ID from the URL
$residentId = intval($_GET['resident_id']);

// Fetch resident details for confirmation
$residentQuery = "SELECT * FROM residents WHERE resident_id = $residentId";
$residentResult = $conn->query($residentQuery);

// Check if resident exists
if ($residentResult->num_rows === 0) {
    // Redirect back to the residents list or show an error message
    header("Location: residents.php");
    exit();
}

$resident = $residentResult->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Resident</title>
    <link rel="stylesheet" href="stylesdeleteresident.css">
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - Delete Resident</h1>
        <div class="user-info">
            <p>Admin: [Admin Name]</p>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <section>
        <h2>Delete Resident</h2>
        <p>Are you sure you want to delete this resident?</p>
        <br>

        <p><strong>Name:</strong> <?php echo htmlspecialchars($resident['resident_name']); ?></p>
        <p><strong>National ID:</strong> <?php echo htmlspecialchars($resident['resident_id']); ?></p>
        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($resident['resident_DOB']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($resident['email']); ?></p>
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($resident['resident_contact']); ?></p>
        <p><strong>Room Number:</strong> <?php echo htmlspecialchars($resident['resident_room_no']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($resident['status'] == 1 ? 'Active' : 'Inactive'); ?></p>
        <p><strong>Created At:</strong> <?php echo htmlspecialchars($resident['created_at']); ?></p>

        <form method="POST" action="">
            <input type="hidden" name="resident_id" value="<?php echo htmlspecialchars($residentId); ?>">
            <input type="submit" value="Delete Resident" class="submit-btn">
        </form>
        <a href="residents.php" class="back-btn">Cancel</a>
    </section>
    
    <?php
    // Delete resident from the database if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $deleteQuery = "DELETE FROM residents WHERE resident_id = $residentId";
        if ($conn->query($deleteQuery) === TRUE) {
            echo "<p class='success'>Resident deleted successfully.</p>";
            header("Location: residents.php");
            exit();
        } else {
            echo "<p class='error'>Error: " . $deleteQuery . "<br>" . $conn->error . "</p>";
        }
    }

    $conn->close(); 
    ?>
</body>
</html>
