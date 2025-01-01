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
    $new_name = $_POST['resident_name'];
    $new_national_id = $_POST['resident_id'];
    $new_age = $_POST['resident_DOB'];
    $new_email = $_POST['email'];
    $new_phone = $_POST['resident_contact'];
    $new_room_id = $_POST['resident_room_no'];
    $new_username = $_POST['username'];
    $new_password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $new_profile_picture = $_FILES['profile_picture']['name'] ? $_FILES['profile_picture']['name'] : null;

    $updateQuery = "UPDATE residents SET resident_name = ?, resident_id = ?, resident_DOB = ?, email = ?, resident_contact = ?, resident_room_no = ?, username = ?" .
        ($new_password ? ", password = ?" : "") .
        ($new_profile_picture ? ", profile_picture = ?" : "") .
        " WHERE id = ?";

    $stmt = $conn->prepare($updateQuery);

    if (!$stmt) {
        die('Error preparing the statement: ' . $conn->error);
    }

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

    if ($new_profile_picture) {
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], "uploads/" . $new_profile_picture);
    }
}

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
    <link rel="stylesheet" href="view_meal_plans.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" style="position: fixed; top: 0; left: 0; height: 100%; width: 250px; background-color: #2c2f33; color: #fff; padding: 20px; box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);">

        <h2><i class="fas fa-user-shield"></i> Resident Panel</h2>
        <ul>
            <li><a href="resident_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="active"><a href="edit_profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="resident_view_meal_plans.php"><i class="fas fa-utensils"></i> Meals</a></li>
            <li><a href="update_checkin_checkout.php"><i class="fas fa-calendar-check"></i> Check-in/out</a></li>
            <li><a href="Re_view_calendar.php"><i class="fas fa-calendar"></i> Events</a></li>
            <li><a href="transaction.php"><i class="fa fa-credit-card"></i> Monthly Fee</a></li>
            <li><a href="#support"><i class="fas fa-headset"></i> Support</a></li>
        </ul>
        <button onclick="window.location.href='edit_profile.php'" class="edit-btn"><i class="fas fa-user-edit"></i> Edit Profile</button>
        <button onclick="window.location.href='login.php'" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header style="max: width 200px; margin-left: 250px;">
            <div class="header-left">
                <img src="The_Salvation_Army.png" alt="Logo" class="logo">
            </div>
            <center><b><h2 style="text-align:left;">Salvation Army Girls Hostel</h2></b></center>
            <h4  style="margin-right:20px;">Welcome,<?php echo htmlspecialchars($residentData['username']); ?></h4>
        </header>
        <div class="breadcrumbs">

        <a href="resident_dashboard.php" class="breadcrumb-item">

    <i class="fas fa-home"></i> Back to Dashboard
</a>

</div>



        <center><form method="POST" action="" enctype="multipart/form-data" style="margin-left:550px; border-radius:10px;box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.5);">
            <h1>Edit Profile</h1>
            <label for="resident_name">Name:</label>
            <input type="text" name="resident_name" id="resident_name" value="<?php echo htmlspecialchars($residentData['resident_name']); ?>" required>

            <label for="resident_id">National ID:</label>
            <input type="text" name="resident_id" id="resident_id" value="<?php echo htmlspecialchars($residentData['resident_id']); ?>" required>

            <label for="resident_DOB">Date of Birth:</label>
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

            <button type="submit">Update Profile</button>
        </form></center>
        
    </div>
</body>
</html>
