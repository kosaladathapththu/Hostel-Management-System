<?php
include 'db_connect.php'; // Include database connection

// Updated query to reflect the renamed columns
$residentsQuery = "
    SELECT 
        r.id, 
        r.resident_name AS name, 
        r.resident_id AS national_id, 
        r.resident_DOB AS age, 
        r.email, 
        r.resident_contact AS phone, 
        rm.room_number, 
        r.status, 
        r.created_at 
    FROM residents r 
    LEFT JOIN Rooms rm 
    ON r.resident_room_no = rm.room_id"; // Updated join condition
$residentsResult = $conn->query($residentsQuery);
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Residents</title>
    <link rel="stylesheet" href="stylesresident.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - Residents</h1>
        <div class="user-info">
            <p>Admin: [Admin Name]</p>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <section>
        <h2>Residents List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>National ID</th>
                    <th>Date of Birth</th> <!-- Changed 'Age' to reflect DOB -->
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Room Number</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
        <tbody>
            <?php while ($row = $residentsResult->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo isset($row['national_id']) ? $row['national_id'] : 'N/A'; ?></td>
                <td><?php echo isset($row['age']) ? $row['age'] : 'N/A'; ?></td>
                <td><?php echo isset($row['email']) ? $row['email'] : 'N/A'; ?></td>
                <td><?php echo isset($row['phone']) ? $row['phone'] : 'N/A'; ?></td>
                <td><?php echo isset($row['room_number']) ? $row['room_number'] : 'N/A'; ?></td>
                <td><?php echo $row['status'] === 'active' ? 'Active' : 'Inactive'; ?></td>
                <td><?php echo isset($row['created_at']) ? $row['created_at'] : 'N/A'; ?></td>
                <td>
                    <a href="edit_resident.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                    <a href="delete_resident.php?id=<?php echo $row['id']; ?>" class="delete-btn">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        </table>
        <div class="nav-buttons">
            <a href="add_resident.php" class="add-btn">Add New Resident</a>
            <a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a> 
            <a href="waiting_list.php" class="waiting-list-btn">View Waiting List</a>
        </div>
    </section>
</body>
</html>

<?php
$conn->close(); 
?>
