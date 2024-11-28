<?php
include 'db_connect.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $resident_name = $_POST['resident_name'];
    $resident_id = $_POST['resident_id'];
    $resident_DOB = $_POST['resident_DOB'];
    $email = $_POST['email'];
    $resident_contact = $_POST['resident_contact'];
    $resident_room_no = $_POST['resident_room_no'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // File upload directory
    $targetDir = "uploads/";
    $profilePicturePath = "";
    $residentFormPath = "";

    // Handle profile picture upload
    if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0) {
        $fileName = basename($_FILES["profile_picture"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        $allowedTypes = array("jpg", "jpeg", "png", "gif");

        if (in_array(strtolower($fileType), $allowedTypes) && move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath)) {
            $profilePicturePath = $targetFilePath;
        } else {
            echo "Error uploading profile picture or invalid file format.";
        }
    }

    // Handle resident form upload
    if (isset($_FILES["resident_form"]) && $_FILES["resident_form"]["error"] == 0) {
        $formFileName = basename($_FILES["resident_form"]["name"]);
        $formTargetFilePath = $targetDir . $formFileName;
        $formFileType = pathinfo($formTargetFilePath, PATHINFO_EXTENSION);
        $allowedFormTypes = array("jpg", "jpeg", "png", "gif", "pdf");

        if (in_array(strtolower($formFileType), $allowedFormTypes) && move_uploaded_file($_FILES["resident_form"]["tmp_name"], $formTargetFilePath)) {
            $residentFormPath = $formTargetFilePath;
        } else {
            echo "Error uploading resident form or invalid file format.";
        }
    }

    // Prepare the SQL query to insert the data into the residents table
    $query = $conn->prepare("INSERT INTO residents (resident_name, resident_id, resident_DOB, email, resident_contact, resident_room_no, username, password, profile_picture, resident_form, status, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active', NOW())");

    // Check if the query preparation was successful
    if (!$query) {
        die('MySQL prepare error: ' . $conn->error);
    }

    // Bind the parameters
    $query->bind_param("ssssssssss", $resident_name, $resident_id, $resident_DOB, $email, $resident_contact, $resident_room_no, $username, $password, $profilePicturePath, $residentFormPath);

    // Execute the query
    if ($query->execute()) {
        // Success message or any custom action after insert
        echo "Resident added successfully!";
    } else {
        echo "Error: " . $query->error;
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resident Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="container">
        <h2>Resident Registration</h2>
        <form method="POST" action="add_resident.php" enctype="multipart/form-data">
            <label>Resident Name:</label>
            <input type="text" name="resident_name" required>
            
            <label>Resident ID:</label>
            <input type="text" name="resident_id" required>
            
            <label>Date of Birth:</label>
            <input type="date" name="resident_DOB" required>
            
            <label>Email:</label>
            <input type="email" name="email" required>
            
            <label>Contact Number:</label>
            <input type="text" name="resident_contact" required>
            
            <label>Room Number:</label>
            <input type="text" name="resident_room_no" required>
            
            <label>Username:</label>
            <input type="text" name="username" required>
            
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <div class="file-upload-wrapper">
                <label>Profile Picture:</label>
                <input type="file" name="profile_picture" accept="image/*" required>
            </div>
            
            <div class="file-upload-wrapper">
                <label>Resident Form (PDF/Image):</label>
                <input type="file" name="resident_form" accept=".pdf, image/*" required>
            </div>
            
            <button type="submit">Add Resident</button>
        </form>
    </div>
</body>
</html>
