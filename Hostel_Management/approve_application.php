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
<?php
session_start();
include 'db_connect.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Check if the application ID is provided
if (isset($_GET['id'])) {
    $application_id = $_GET['id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Fetch applicant details from job_applications
        $query = "SELECT * FROM job_applications WHERE application_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $application_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $applicant = $result->fetch_assoc();
        $stmt->close();

        if (!$applicant) {
            throw new Exception("Applicant data not found or invalid application ID.");
        }

        // Map fields for employees table
        $name = $applicant['applicant_name'];
        $email = $applicant['contact_email'];
        $phone = $applicant['contact_phone'];
        $position = 'New Hire'; // Default position, can be updated dynamically
        $status = 'active';
        $created_at = date("Y-m-d H:i:s");
        $national_id = ''; // Add proper mapping if available
        $password = password_hash("defaultpassword", PASSWORD_BCRYPT); // Default password

        // Insert into employees table
        $insert_query = "
            INSERT INTO employees 
            (name, position, email, phone, status, created_at, national_id, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssssssss", $name, $position, $email, $phone, $status, $created_at, $national_id, $password);
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert into employees: " . $stmt->error);
        }
        $stmt->close();

        // Update the application status in job_applications
        $update_query = "UPDATE job_applications SET status = 'approved' WHERE application_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("i", $application_id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to update application status: " . $stmt->error);
        }
        $stmt->close();

        // Commit the transaction
        $conn->commit();

        // Redirect with success message
        header("Location: view_applications.php?message=Application Approved");
        exit();

    } catch (Exception $e) {
        // Rollback the transaction in case of any failure
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }

} else {
    echo "Invalid application ID.";
}
?>
