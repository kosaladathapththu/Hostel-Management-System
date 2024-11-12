<?php
session_start();
include 'db_connect.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Check for valid application ID
if (isset($_GET['id'])) {
    $application_id = $_GET['id'];

    // Fetch applicant details
    $query = "SELECT * FROM job_applications WHERE application_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $applicant = $result->fetch_assoc();
    $stmt->close();

    // Check if applicant data was retrieved successfully
    if (!$applicant) {
        die("Error: Applicant data not found or invalid application ID.");
    }

    // Set default values for fields
    $name = $applicant['applicant_name'] ?? 'Unknown'; // from job_applications
    $email = $applicant['contact_email'] ?? 'noemail@example.com'; // from job_applications
    $phone = $applicant['contact_phone'] ?? '0000000000'; // from job_applications
    $position = 'New Hire'; // This can be customized or linked to vacancy_id if needed
    $status = 'active'; // Assuming 'active' is the initial status for employees
    $created_at = date("Y-m-d H:i:s"); // Set to the current date and time
    $national_id = ''; // Add an appropriate value if available, else leave empty
    $password = password_hash("defaultpassword", PASSWORD_BCRYPT); // Default password

    // Insert applicant data into the employees table
    $insert_query = "INSERT INTO employees (name, position, email, phone, status, created_at, national_id, password) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssssssss", $name, $position, $email, $phone, $status, $created_at, $national_id, $password);
    $stmt->execute();
    $stmt->close();

    // Update the application status to 'approved'
    $update_query = "UPDATE job_applications SET status = 'approved' WHERE application_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $stmt->close();

    // Redirect with a success message
    header("Location: view_applications.php?message=Application Approved");
    exit();
} else {
    echo "Invalid application ID.";
}
?>
