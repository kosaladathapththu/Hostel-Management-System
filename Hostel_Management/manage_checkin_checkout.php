<?php
include 'db_connect.php';

// Modify this query based on the correct column name in your residents table
$query = "SELECT b.booking_id, r.resident_name, b.check_in_date, b.check_out_date 
          FROM bookings b
          JOIN residents r ON b.resident_id = r.id
          WHERE b.status = 'pending_approval'";

$result = $conn->query($query);

if (!$result) {
    echo "Error: " . $conn->error;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Check-ins/Check-outs</title>
    <link rel="stylesheet" href="manage_checkin_checkout.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - Check-ins/Check-outs</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>

    <table>
        <thead>
            <tr>
                <th>Resident Name</th>
                <th>Requested Check-in Date</th>
                <th>Requested Check-out Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['resident_name']; ?></td> <!-- Use correct column name here -->
                <td><?php echo $row['check_in_date']; ?></td>
                <td><?php echo $row['check_out_date']; ?></td>
                <td>
                    <a href="approve_checkin_checkout.php?id=<?php echo $row['booking_id']; ?>" class="approve-btn">Approve</a>
                    <a href="decline_checkin_checkout.php?id=<?php echo $row['booking_id']; ?>" class="decline-btn">Decline</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="dashboard-button">Dashboard</a>
</body>
</html>

<?php
$conn->close();
?>
