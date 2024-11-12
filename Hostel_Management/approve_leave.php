<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $application_id = $_GET['id'];

    // Fetch the leave application details
    $query = "SELECT employee_id, start_date, end_date FROM leave_applications WHERE application_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $leaveRequest = $result->fetch_assoc();
    $stmt->close();

    // Calculate the number of days for the leave request
    $start_date = new DateTime($leaveRequest['start_date']);
    $end_date = new DateTime($leaveRequest['end_date']);
    $interval = $start_date->diff($end_date);
    $leave_days = $interval->days + 1; // Include both start and end days

    // Update the leave application status to 'approved'
    $query = "UPDATE leave_applications SET status = 'approved' WHERE application_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $stmt->close();

    // Deduct the leave days from the employee's leave balance
    $employee_id = $leaveRequest['employee_id'];
    $query = "UPDATE employees SET leave_balance = leave_balance - ? WHERE employee_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $leave_days, $employee_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Leave request approved and leave balance updated successfully.";
    } else {
        echo "Error updating leave balance.";
    }

    $stmt->close();
}

$conn->close();
header("Location: view_leave_requests.php");
exit();
?>
