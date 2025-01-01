<?php
include 'db_connect.php'; // Include your database connection file
session_start();

if (!isset($_SESSION['guest_id'])) {
    header("Location: guest_login.php");
    exit();
}

$guest_name = $_SESSION['guest_name'];

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
    $matron_id = $_POST['matron_id']; // Get matron ID from form

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
    $query = $conn->prepare("INSERT INTO applicants (name, national_id, age, email, phone, room_id, username, password, matron_id, status, profile_picture, resident_form, application_date)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'waiting', ?, ?, NOW())");
    
    $query->bind_param("sssssssssss", $name, $national_id, $age, $email, $phone, $room_id, $username, $password, $matron_id, $profilePicturePath, $residentFormPath);

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
    <style>

    form {
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 750px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #333;
    }

    input[type="text"],
    input[type="number"],
    input[type="email"],
    input[type="password"],
    input[type="file"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }

    .file-upload-wrapper {
        margin-bottom: 15px;
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: #007BFF;
        border: none;
        border-radius: 5px;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="apply_employee_vacancies.css">
    <link rel="stylesheet" href="guest_dashboard.css">
</head>
<body>
<div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="logo-container">
                <img src="The_Salvation_Army.png" alt="Salvation Army Logo" class="logo"><br><br>


</div>
<div><center><h3 styles="margin-bottom:20px;">Salvation Army <br>Girl's Hostel</h3></center>
</div>

            
            
            <nav class="sidebar-menu">
                <ul>
                    <li class="active">
                        <a href="guest_dashboard.php">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="social_services.php">
                            <i class="fas fa-gopuram"></i>
                            <span>Social Services</span>
                        </a>
                    </li>
                    <li>
                        <a href="view_room_vacancy.php">
                            <i class="fas fa-bed "></i>
                            <span>Room Vacancies</span>
                        </a>
                    </li>
                    <li>
                        <a href="register.php">
                            <i class="fas fa-file"></i>
                            <span>Apply Residency</span>
                        </a>
                    </li>
                    <li>
                        <a href="employee_vacancies.php">
                            <i class="fas fa-id-card "></i>
                            <span>Job Vacancies</span>
                        </a>
                    </li>
                    <li>
                        <a href="apply_for_job.php">
                            <i class="fas fa-square"></i>
                            <span>Apply Employee</span>
                        </a>
                    </li>
                    <li>
                        <a href="#profile">
                            <i class="fas fa-user"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="guest_logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
                <!-- Main Content Area -->
                <main class="main-content">
            <header class="dashboard-header">
                <div class="header-left">
                    <h1>Welcome, <?php echo htmlspecialchars($guest_name); ?>!</h1>
                </div>
                <div class="header-right">
                    <div class="notification-icon">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="user-profile">
                        <img src="guest.jpg" alt="Profile Picture">
                    </div>
                </div>

</header>
                
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
