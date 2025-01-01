<?php
include 'db_connect.php';

if (isset($_GET['application_id'])) {
    $application_id = $_GET['application_id'];

    // Fetch the leave application details
    $query = "SELECT employee_id, start_date, end_date FROM leave_applications WHERE application_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $leaveRequest = $result->fetch_assoc();

        // Calculate the number of days for the leave request
        $start_date = new DateTime($leaveRequest['start_date']);
        $end_date = new DateTime($leaveRequest['end_date']);
        $interval = $start_date->diff($end_date);
        $leave_days = $interval->days + 1; // Include both start and end days

        // Get the admin ID from the session (the logged-in admin)
        session_start();
        $admin_id = $_SESSION['admin_id'];

        // Update the leave application status to 'approved' and set the admin_id
        $query = "UPDATE leave_applications SET status = 'approved', admin_id = ? WHERE application_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $admin_id, $application_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Deduct the leave days from the employee's leave balance
            $employee_id = $leaveRequest['employee_id'];
            $query = "UPDATE employees SET leave_balance = leave_balance - ? WHERE employee_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $leave_days, $employee_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Leave request approved and leave balance updated successfully.";
            } else {
                echo "Error updating employee's leave balance.";
            }
        } else {
            echo "Error updating leave application status.";
        }
    } else {
        echo "Leave application not found.";
    }

    $stmt->close();
} else {
    echo "Application ID not provided.";
}

$conn->close();
header("Location: view_leave_requests.php");
exit();
?>
