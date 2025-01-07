<?php
session_start(); // Start session for authentication check

// Check if the user is logged in as a matron
if (!isset($_SESSION['matron_id'])) {
    header("Location: matron_auth.php"); // Redirect if not logged in
    exit();
}

include 'db_connect.php'; // Include database connection

// Fetch the matron's details (Extra safety check)
$matron_id = $_SESSION['matron_id'];
$matronQuery = "SELECT first_name FROM Matrons WHERE matron_id = ?";
$stmt = $conn->prepare($matronQuery);
$stmt->bind_param("i", $matron_id);
$stmt->execute();
$matronResult = $stmt->get_result();

if ($matronResult->num_rows === 0) {
    header("Location: matron_auth.php"); // Redirect if matron not found
    exit();
}

// Assign matron's first name
$matronData = $matronResult->fetch_assoc();
$matron_first_name = htmlspecialchars($matronData['first_name']); // Sanitize output

// Initialize error messages
$errors = [];
$showSuccessMessage = false;

// Ensure the uploads directory exists
$targetDir = "uploads/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form inputs
    $resident_name = htmlspecialchars(trim($_POST['resident_name']));
    $resident_id = htmlspecialchars(trim($_POST['resident_id']));
    $resident_DOB = $_POST['resident_DOB'];
    $email = htmlspecialchars(trim($_POST['email']));
    $resident_contact = htmlspecialchars(trim($_POST['resident_contact']));
    $resident_room_no = htmlspecialchars(trim($_POST['resident_room_no']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    if (empty($resident_name)) {
        $errors[] = "Resident name is required.";
    }
    if (empty($resident_id)) {
        $errors[] = "Resident ID is required.";
    }
    if (empty($resident_DOB) || !DateTime::createFromFormat('Y-m-d', $resident_DOB)) {
        $errors[] = "A valid date of birth is required (format: YYYY-MM-DD).";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email address is required.";
    }
    if (empty($resident_contact) || !preg_match("/^\d+$/", $resident_contact)) {
        $errors[] = "A valid contact number is required.";
    }
    if (empty($resident_room_no)) {
        $errors[] = "Room number is required.";
    }
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    // File upload validations
    $profilePicturePath = "";
    $residentFormPath = "";

    if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0) {
        $fileName = basename($_FILES["profile_picture"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];

        if (!in_array(strtolower($fileType), $allowedTypes)) {
            $errors[] = "Only JPG, JPEG, PNG, and GIF formats are allowed for the profile picture.";
        } elseif (!move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath)) {
            $errors[] = "Error uploading the profile picture.";
        } else {
            $profilePicturePath = $targetFilePath;
        }
    } else {
        $errors[] = "Profile picture is required.";
    }

    if (isset($_FILES["resident_form"]) && $_FILES["resident_form"]["error"] == 0) {
        $formFileName = basename($_FILES["resident_form"]["name"]);
        $formTargetFilePath = $targetDir . $formFileName;
        $formFileType = pathinfo($formTargetFilePath, PATHINFO_EXTENSION);
        $allowedFormTypes = ["jpg", "jpeg", "png", "gif", "pdf"];

        if (!in_array(strtolower($formFileType), $allowedFormTypes)) {
            $errors[] = "Only JPG, JPEG, PNG, GIF, and PDF formats are allowed for the resident form.";
        } elseif (!move_uploaded_file($_FILES["resident_form"]["tmp_name"], $formTargetFilePath)) {
            $errors[] = "Error uploading the resident form.";
        } else {
            $residentFormPath = $formTargetFilePath;
        }
    } else {
        $errors[] = "Resident form is required.";
    }

    // If no errors, proceed to insert into the database
    if (empty($errors)) {
        $password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

        // Prepare the SQL query to insert the data
        $query = $conn->prepare("INSERT INTO residents (resident_name, resident_id, resident_DOB, email, resident_contact, resident_room_no, username, password, profile_picture, resident_form, status, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active', NOW())");

        if ($query) {
            $query->bind_param("ssssssssss", $resident_name, $resident_id, $resident_DOB, $email, $resident_contact, $resident_room_no, $username, $password, $profilePicturePath, $residentFormPath);

            if ($query->execute()) {
                $showSuccessMessage = true;
            } else {
                $errors[] = "Error inserting data: " . $query->error;
            }
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resident Registration</title>
    <link rel="stylesheet" href="stylesresident.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Popup styles */
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #4CAF50;
            color: white;
            padding: 20px 40px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            text-align: center;
            display: none;
        }

        .popup.show {
            display: block;
            animation: fadeInOut 3s;
        }

        @keyframes fadeInOut {
            0%, 100% { opacity: 0; }
            10%, 90% { opacity: 1; }
        }
    </style>
</head>
<body>
    <!-- Popup Message -->
    <div id="successPopup" class="popup <?php echo $showSuccessMessage ? 'show' : ''; ?>">
        Resident added successfully!
    </div>

    <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Matron Panel</h2>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="residents.php"><i class="fas fa-users"></i> Residents</a></li>
            <li><a href="bookings.php"><i class="fas fa-calendar-check"></i> Check-ins/Outs</a></li>
            <li><a href="rooml.php"><i class="fas fa-door-open"></i> Rooms</a></li>
            <li><a href="payments.php"><i class="fas fa-money-check-alt"></i> Payments</a></li>
            <li><a href="view_suppliers.php"><i class="fas fa-truck"></i> Suppliers</a></li>
            <li><a href="view_order.php"><i class="fas fa-receipt"></i> Orders</a></li>
            <li><a href="view_inventory.php"><i class="fas fa-boxes"></i> Inventory</a></li>
            <li><a href="view_calendar.php"><i class="fas fa-calendar"></i> Events</a></li>
            <li><a href="view_meal_plans.php"><i class="fas fa-utensils"></i> Meal Plans</a></li>
        </ul>
        <button onclick="window.location.href='matron_logout.php'" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>

    <div class="main-content">
        <header>
            <div class="header-left">
                <img src="The_Salvation_Army.png" alt="Logo" class="logo"> 
            </div>
            <center><b><h1>Salvation Army Girls Hostel</h1></b></center>
            <div class="header-right">
                <p>Welcome, <?php echo htmlspecialchars($matron_first_name); ?></p>
            </div>
        </header>

        <section>
            <h2 style="margin-top:20px;">Resident Registration</h2>
            <div class="breadcrumbs">
                <a href="residents.php" class="breadcrumb-item">
                    <i class="fas fa-step-backward "></i>Back to Resident List
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="dashboard.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="waiting_list.php" class="breadcrumb-item">
                    <i class="fas fa-list"></i> Waiting List
                </a>
            </div>

            <!-- Validation Messages Section -->
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Resident Registration Form -->
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
        </section>
    </div>

    <script>
        // Automatically remove popup after animation and redirect
        const popup = document.getElementById('successPopup');
        if (popup && popup.classList.contains('show')) {
            setTimeout(() => {
                popup.classList.remove('show');
                window.location.href = 'residents.php';
            }, 3000);
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>