<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['employee'])) {
    header("Location: employee_login.php");
    exit;
}

$employee_id = $_SESSION['employee']['employee_id'];

$query = "SELECT * FROM leave_applications WHERE employee_id = $employee_id ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Leave Status</title>
    <link rel="stylesheet" href="employee_view_leave_status.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <h1>Your Leave Status</h1>
    <table>
        <tr>
            <th>Application ID</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th>Reason</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['application_id']); ?></td>
                <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td><?php echo htmlspecialchars($row['reason']); ?></td>
            </tr>
        <?php } ?>
    </table>
    <br>
    <button onclick="window.location.href='apply_leave.php'">Apply for Leave</button>
    <button onclick="window.location.href='employee_dashboard.php'">Back to Dashboard</button>
</body>
</html>

