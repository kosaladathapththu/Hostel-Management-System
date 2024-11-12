<?php
session_start();
include 'db_connect.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch all pending applications
$query = "SELECT * FROM job_applications WHERE status = 'pending'";
$result = mysqli_query($conn, $query);

// Check for query execution errors
if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Job Applications</title>
    <link rel="stylesheet" href="view_applications.css"> <!-- Link to the CSS file if saved separately -->
</head>
<body>
    <h1>Pending Job Applications</h1>
    <table>
        <tr>
            <th>Application ID</th>
            <th>Applicant Name</th>
            <th>Email</th>
            <th>Position</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['application_id']; ?></td>
                <td><?php echo isset($row['applicant_name']) ? $row['applicant_name'] : 'N/A'; ?></td>
                <td><?php echo isset($row['contact_email']) ? $row['contact_email'] : 'N/A'; ?></td>
                <td><?php echo isset($row['vacancy_id']) ? $row['vacancy_id'] : 'N/A'; ?></td>
                <td>
                    <a href="approve_application.php?id=<?php echo $row['application_id']; ?>">Approve</a> |
                    <a href="reject_application.php?id=<?php echo $row['application_id']; ?>">Reject</a>
                </td>
            </tr>
        <?php } ?>
    </table>
    <br>
    <button onclick="window.location.href='admin_dashboard.php'">Back to Dashboard</button>
</body>
</html>

