<?php
include 'db_connect.php';
session_start();

// Ensure the supplier is logged in
if (!isset($_SESSION['supplier_id'])) {
    header("Location: supplier_login.php");
    exit();
}

$supplier_id = $_SESSION['supplier_id'];

// Fetch supplier's information
$supplierQuery = "SELECT username FROM suppliers WHERE supplier_id = ?";
$stmt = $conn->prepare($supplierQuery);
$stmt->bind_param("i", $supplier_id);
$stmt->execute();
$supplierResult = $stmt->get_result();
$supplier = $supplierResult->fetch_assoc();

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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
    form {
    max-width: 400px;
    margin: 30px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: black;
}

.form-group input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    color: white;
    box-sizing: border-box;
}

.form-group input:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 4px rgba(0, 123, 255, 0.5);
}

        </style>
    <link rel="stylesheet" href="new_supplier.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Supplier Panel</h2>
        <div class="profile-info">
            <p>Welcome, <?php echo htmlspecialchars($supplier['username']); ?></p>
        </div>
        <ul>
            <li><a href="new_supplier.php"><i class="fas fa-home"></i> Dashboard</a></li><br>
            <li><a href="supplier_dashboard.php"><i class="fas fa-users"></i> Order Management</a></li><br>
            <li><a href="view_supplier_contracts.php"><i class="fas fa-bars"></i> Supplier Contracts</a></li><br>
            <button onclick="window.location.href='edit_supplier_profile.php'" class="edit-btn"><i class="fas fa-user-edit"></i> Edit Profile</button>
            <button onclick="window.location.href='supplier_logout.php'" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div class="header-left">
                <img src="The_Salvation_Army.png" alt="Logo" class="logo">
            </div>
            <center><b><h1>Salvation Army Girls Hostel</h1></b></center>
            <div class="header-right">
                <p>Welcome, <?php echo htmlspecialchars($supplier['username']); ?></p>
            </div>
        </header>

        <h2 style="margin-top:40px; margin-left:20px;">Edit Profile</h2>
        <div class="breadcrumbs">
            <a href="supplier_dashboard.php" class="breadcrumb-item">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </div>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">New Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($supplier['username']); ?>" required><br><br>
            </div>

            <div class="form-group">
                <label for="password">New Password (optional):</label>
                <input type="password" id="password" name="password"><br><br>
            </div>

            <button type="submit" class="submit-button">Update Profile</button><br><br>
        </form>

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
    </div>
</body>
</html>
