<?php
include 'db_connect.php'; // Include database connection

session_start(); // Start session

// Check if resident is logged in; if not, redirect to login page
if (!isset($_SESSION['resident_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch resident_id from session
$resident_id = $_SESSION['resident_id'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gather POST data
    $new_name = $_POST['resident_name'];
    $new_national_id = $_POST['resident_id']; // Make sure this matches the database column
    $new_age = $_POST['resident_DOB']; // This should map to resident_DOB
    $new_email = $_POST['email'];
    $new_phone = $_POST['resident_contact'];
    $new_room_id = $_POST['resident_room_no'];
    $new_username = $_POST['username'];
    $new_password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null; // Hashing password only if provided
    $new_profile_picture = $_FILES['profile_picture']['name'] ? $_FILES['profile_picture']['name'] : null;

    // Update query with proper fields
    $updateQuery = "UPDATE residents SET resident_name = ?, resident_id = ?, resident_DOB = ?, email = ?, resident_contact = ?, resident_room_no = ?, username = ?" . 
                    ($new_password ? ", password = ?" : "") . 
                    ($new_profile_picture ? ", profile_picture = ?" : "") . 
                    " WHERE id = ?";

    // Prepare statement
    $stmt = $conn->prepare($updateQuery);

    if (!$stmt) {
        die('Error preparing the statement: ' . $conn->error); // Check for errors in query preparation
    }

    // Bind parameters based on which fields are filled
    if ($new_password && $new_profile_picture) {
        $stmt->bind_param("ssissssssi", $new_name, $new_national_id, $new_age, $new_email, $new_phone, $new_room_id, $new_username, $new_password, $new_profile_picture, $resident_id);
    } elseif ($new_password) {
        $stmt->bind_param("ssisssssi", $new_name, $new_national_id, $new_age, $new_email, $new_phone, $new_room_id, $new_username, $new_password, $resident_id);
    } elseif ($new_profile_picture) {
        $stmt->bind_param("ssissssss", $new_name, $new_national_id, $new_age, $new_email, $new_phone, $new_room_id, $new_username, $new_profile_picture, $resident_id);
    } else {
        $stmt->bind_param("ssisssss", $new_name, $new_national_id, $new_age, $new_email, $new_phone, $new_room_id, $new_username, $resident_id);
    }

    // Execute statement and check for errors
    if ($stmt->execute()) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    // Handle file upload for profile picture
    if ($new_profile_picture) {
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], "uploads/" . $new_profile_picture); // Ensure this directory exists and is writable
    }
}

// Fetch current details to show in the form
$residentQuery = "SELECT resident_name, resident_id, resident_DOB, email, resident_contact, resident_room_no, username, profile_picture FROM residents WHERE id = ?";
$residentStmt = $conn->prepare($residentQuery);
$residentStmt->bind_param("i", $resident_id);
$residentStmt->execute();
$residentResult = $residentStmt->get_result();
$residentData = $residentResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="edit_profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-content"> 
            <h1><center>Edit Profile</center></h1>
            <label for="resident_name">Name:</label>
            <input type="text" name="resident_name" id="resident_name" value="<?php echo htmlspecialchars($residentData['resident_name']); ?>" required>
            
            <label for="resident_id">National ID:</label>
            <input type="text" name="resident_id" id="resident_id" value="<?php echo htmlspecialchars($residentData['resident_id']); ?>" required>

            <label for="resident_DOB">Age:</label>
            <input type="date" name="resident_DOB" id="resident_DOB" value="<?php echo htmlspecialchars($residentData['resident_DOB']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($residentData['email']); ?>" required>

            <label for="resident_contact">Phone:</label>
            <input type="text" name="resident_contact" id="resident_contact" value="<?php echo htmlspecialchars($residentData['resident_contact']); ?>" required>

            <label for="resident_room_no">Room Number:</label>
            <input type="text" name="resident_room_no" id="resident_room_no" value="<?php echo htmlspecialchars($residentData['resident_room_no']); ?>" required>

            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($residentData['username']); ?>" required>

            <label for="password">New Password:</label>
            <input type="password" name="password" id="password">

            <label for="profile_picture">Profile Picture:</label>
            <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
        </div> <!-- End of form-content -->

        <button type="submit">Update Profile</button>
        <div><a href="resident_dashboard.php">Back to Dashboard</a></div>
    </form><br>
    
</body>
</html>
