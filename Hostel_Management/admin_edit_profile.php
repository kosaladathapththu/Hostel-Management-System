<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$message = "";

// Fetch current admin details
$query = "SELECT admin_name, username, email FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_name = $_POST['admin_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $new_password = $_POST['password'];
    
    // Update profile info, optionally with a new password
    if (!empty($new_password)) {
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $updateQuery = "UPDATE admin SET admin_name = ?, username = ?, email = ?, password = ? WHERE admin_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssssi", $admin_name, $username, $email, $hashedPassword, $admin_id);
    } else {
        $updateQuery = "UPDATE admin SET admin_name = ?, username = ?, email = ? WHERE admin_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssi", $admin_name, $username, $email, $admin_id);
    }

    if ($stmt->execute()) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile. Please try again.";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Admin Profile</title>
    <link rel="stylesheet" href="admin_edit_profile.css"> <!-- Link to the external CSS -->
</head>
<body>
    <div class="left-background"></div>
    
    <div class="header">
        <div class="header-title">Admin Dashboard</div>
        <div class="admin-info">
            <span><?php echo htmlspecialchars($admin['admin_name']); ?></span>
            <a href="admin_logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    
    <div class="edit-profile-section">
        
        <form method="POST" class="admin-profile-form">
            <h2>Edit admin profile</h2><br>
            <label for="admin_name">Admin Name:</label>
            <input type="text" id="admin_name" name="admin_name" value="<?php echo htmlspecialchars($admin['admin_name']); ?>" required><br>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required><br>

            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" placeholder="Leave blank to keep current password"><br>

            <button type="submit">Save Changes</button>
        </form>
        <?php if ($message) echo "<p>$message</p>"; ?>
    </div>
    <div class="right-background"></div>
</body>
</html>
