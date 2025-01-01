<?php
session_start();

// Check if guest is logged in
if (!isset($_SESSION['guest_id'])) {
    header("Location: guest_login.php");
    exit();
}

// Check if admin is logged in (for admin_id)
if (!isset($_SESSION['admin_id'])) {
    echo "Admin not logged in. Please log in.";
    exit();
}

include 'db_connect.php';

// Fetch data from the form
$vacancy_id = $_POST['vacancy_id'];
$applicant_name = $_POST['applicant_name'];
$contact_email = $_POST['contact_email'];
$contact_phone = $_POST['contact_phone'];
$cover_letter = $_POST['cover_letter'];
$status = 'pending';

// Get the admin ID from the session
$admin_id = $_SESSION['admin_id'];

// Prepare and execute the query to insert the job application
$query = "INSERT INTO job_applications (vacancy_id, applicant_name, contact_email, contact_phone, application_date, cover_letter, status, admin_id) 
          VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("issssss", $vacancy_id, $applicant_name, $contact_email, $contact_phone, $cover_letter, $status, $admin_id);

// Execute the query
if ($stmt->execute()) {
    // Successfully inserted the application
    echo "Job application submitted successfully!";
    header("Location: guest_dashboard.php");
    exit();
} else {
    // Error in submission
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
