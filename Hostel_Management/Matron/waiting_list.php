<?php
include 'db_connect.php'; // Include database connection

// Fetch all applicants with status 'Pending'
$query = "SELECT * FROM applicants WHERE status = ''";
$result = $conn->query($query);
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resident Waiting List</title>
    <link rel="stylesheet" href="waiting.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <h2>Resident Waiting List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>National ID</th>
                <th>Age</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Room ID</th>
                <th>Documents</th> <!-- New Column for Documents -->
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['applicant_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['national_id']; ?></td>
                <td><?php echo $row['age']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['room_id']; ?></td>
                <td>
                    <!-- Links to view profile picture and resident form -->
                    <a href="<?php echo $row['profile_picture']; ?>" target="_blank" class="view-doc-btn">View Profile Picture</a>
                    <br>
                    <a href="<?php echo $row['resident_form']; ?>" target="_blank" class="view-doc-btn">View Resident Form</a>
                </td>
                <td>
                    <a href="approve_resident.php?id=<?php echo $row['applicant_id']; ?>" class="approve-btn">Approve</a>
                    <a href="decline_resident.php?id=<?php echo $row['applicant_id']; ?>" class="decline-btn">Decline</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div class="nav-buttons">
        <a href="residents.php" class="back-btn">Resident List</a>
        <a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a> 
    </div>
</body>
</html>

<?php
$conn->close();
?>
