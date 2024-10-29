<?php
include 'db_connect.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $name = $_POST['name'];
    $national_id = $_POST['national_id'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $room_id = $_POST['room_id'];
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

    // Insert data into the applicants table with status 'Pending'
    $query = $conn->prepare("INSERT INTO applicants (name, national_id, age, email, phone, room_id, username, password, status, profile_picture, resident_form, application_date)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending', ?, ?, NOW())");
    
    $query->bind_param("ssssssssss", $name, $national_id, $age, $email, $phone, $room_id, $username, $password, $profilePicturePath, $residentFormPath);

    if ($query->execute()) {
        // Redirect to login page with awaiting approval message
        header('Location: login.php?msg=awaiting_approval');
        exit();
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
        <form method="POST" action="register.php" enctype="multipart/form-data">
            <label>Name:</label>
            <input type="text" name="name" required>
            
            <label>National ID:</label>
            <input type="text" name="national_id" required>
            
            <label>Age:</label>
            <input type="number" name="age" required>
            
            <label>Email:</label>
            <input type="email" name="email" required>
            
            <label>Phone:</label>
            <input type="text" name="phone" required>
            
            <label>Room ID:</label>
            <input type="number" name="room_id" required>
            
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
            
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
