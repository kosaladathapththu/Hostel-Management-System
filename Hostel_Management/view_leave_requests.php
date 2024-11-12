<?php
session_start();
include 'db_connect.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch all pending leave requests
$query = "SELECT la.application_id, la.employee_id, e.name AS employee_name, la.start_date, la.end_date, la.reason, la.status 
          FROM leave_applications la
          JOIN employees e ON la.employee_id = e.employee_id
          WHERE la.status = 'pending'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Leave Requests</title>
    <link rel="stylesheet" href="view_leave_requests.css">
</head>
<body>
    <h1>Leave Requests</h1>
    <table>
        <tr>
            <th>Application ID</th>
            <th>Employee Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['application_id']; ?></td>
                <td><?php echo $row['employee_name']; ?></td>
                <td><?php echo $row['start_date']; ?></td>
                <td><?php echo $row['end_date']; ?></td>
                <td><?php echo $row['reason']; ?></td>
                <td><?php echo ucfirst($row['status']); ?></td>
                <td>
                    <a href="approve_leave.php?id=<?php echo $row['application_id']; ?>">Approve</a> |
                    <a href="decline_leave.php?id=<?php echo $row['application_id']; ?>">Decline</a>
                </td>
            </tr>
        <?php } ?>
    </table>
    <br>
    <button onclick="window.location.href='admin_dashboard.php'">Back to Dashboard</button>
</body>
</html>


<?php
$conn->close();
?>
