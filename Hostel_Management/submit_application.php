<?php
// Include database connection file
include 'db_connect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize inputs
    $vacancy_id = $_POST['vacancy_id'];
    $applicant_name = mysqli_real_escape_string($conn, $_POST['applicant_name']);
    $contact_email = mysqli_real_escape_string($conn, $_POST['contact_email']);
    $contact_phone = mysqli_real_escape_string($conn, $_POST['contact_phone']);
    $cover_letter = mysqli_real_escape_string($conn, $_POST['cover_letter']);

    // SQL query to insert data into the job_applications table
    $query = "INSERT INTO job_applications (vacancy_id, applicant_name, contact_email, contact_phone, cover_letter, application_date)
              VALUES ('$vacancy_id', '$applicant_name', '$contact_email', '$contact_phone', '$cover_letter', NOW())";

    // Execute the query and check for success
    if (mysqli_query($conn, $query)) {
        echo "<p>✅ Application submitted successfully!</p>";
    } else {
        echo "<p>❌ Error: " . mysqli_error($conn) . "</p>";
    }
} else {
    // Redirect to the application form if accessed directly
    header("Location: apply_for_job.php");
    exit();
}
?>

<!-- Link to go back to the application form or other pages -->
<a href="apply_for_job.php">🔙 Back to Job Application</a>
