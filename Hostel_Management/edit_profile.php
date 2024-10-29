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
    $new_name = $_POST['name'];
    $new_national_id = $_POST['national_id'];
    $new_age = $_POST['age'];
    $new_email = $_POST['email'];
    $new_phone = $_POST['phone'];
    $new_room_id = $_POST['room_id'];
    $new_username = $_POST['username'];
    $new_password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null; // Hashing password only if provided
    $new_profile_picture = $_FILES['profile_picture']['name'] ? $_FILES['profile_picture']['name'] : null;

    // Update query
    $updateQuery = "UPDATE residents SET name = ?, national_id = ?, age = ?, email = ?, phone = ?, room_id = ?, username = ?" . ($new_password ? ", password = ?" : "") . ($new_profile_picture ? ", profile_picture = ?" : "") . " WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);

    // Prepare parameters for binding
    if ($new_password && $new_profile_picture) {
        $stmt->bind_param("ssissssssi", $new_name, $new_national_id, $new_age, $new_email, $new_phone, $new_room_id, $new_username, $new_password, $new_profile_picture, $resident_id);
    } elseif ($new_password) {
        $stmt->bind_param("ssisssssi", $new_name, $new_national_id, $new_age, $new_email, $new_phone, $new_room_id, $new_username, $new_password, $resident_id);
    } elseif ($new_profile_picture) {
        $stmt->bind_param("ssissssss", $new_name, $new_national_id, $new_age, $new_email, $new_phone, $new_room_id, $new_username, $new_profile_picture, $resident_id);
    } else {
        $stmt->bind_param("ssisssss", $new_name, $new_national_id, $new_age, $new_email, $new_phone, $new_room_id, $new_username, $resident_id);
    }

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
$residentQuery = "SELECT name, national_id, age, email, phone, room_id, username, profile_picture FROM residents WHERE id = ?";
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
        <h1><center>Edit Profile</h1></center>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($residentData['name']); ?>" required>
            
            <label for="national_id">National ID:</label>
            <input type="text" name="national_id" id="national_id" value="<?php echo htmlspecialchars($residentData['national_id']); ?>" required>

            <label for="age">Age:</label>
            <input type="number" name="age" id="age" value="<?php echo htmlspecialchars($residentData['age']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($residentData['email']); ?>" required>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($residentData['phone']); ?>" required>

            <label for="room_id">Room ID:</label>
            <input type="text" name="room_id" id="room_id" value="<?php echo htmlspecialchars($residentData['room_id']); ?>" required>

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

