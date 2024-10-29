<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['supplier_id'])) {
    header("Location: supplier_login.php");
    exit();
}

$supplier_id = $_SESSION['supplier_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newUsername = $_POST['username'];
    $newPassword = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Update query
    $updateQuery = "UPDATE suppliers SET username = ?";
    $params = [$newUsername];

    // Add password update only if provided
    if ($newPassword) {
        $updateQuery .= ", password = ?";
        $params[] = $newPassword;
    }

    $updateQuery .= " WHERE supplier_id = ?";
    $params[] = $supplier_id;

    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param(str_repeat('s', count($params) - 1) . 'i', ...$params);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Profile updated successfully!";
        header("Location: supplier_dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating profile: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="edit_supplier_profile.css">
</head>
<body>
    <h1>Edit Profile</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="notification success">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="notification error">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="username">New Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">New Password (optional):</label>
        <input type="password" id="password" name="password"><br>

        <button type="submit">Update Profile</button>
    </form>

    <a href="supplier_dashboard.php">Back to Dashboard</a>
</body>
</html>
