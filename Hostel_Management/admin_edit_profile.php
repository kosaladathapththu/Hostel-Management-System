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
    <title>Edit Profile</title>
</head>
<body>
    <h2>Edit Profile</h2>
    <form method="POST">
        <label>Admin Name:</label>
        <input type="text" name="admin_name" value="<?php echo htmlspecialchars($admin['admin_name']); ?>" required><br>

        <label>Username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required><br>

        <label>New Password:</label>
        <input type="password" name="password" placeholder="Leave blank to keep current password"><br>

        <button type="submit">Save Changes</button>
    </form>
    <?php if ($message) echo "<p>$message</p>"; ?>
</body>
</html>
