<?php
session_start(); // Start session for authentication check

// Check if the user is logged in as a matron
if (!isset($_SESSION['matron_id'])) {
    header("Location: matron_auth.php"); // Redirect to login if not logged in
    exit();
}

include 'db_connect.php'; // Include database connection

$matron_id = $_SESSION['matron_id'];
$successMessage = '';
$errorMessage = '';

// Fetch matron's current details
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT first_name, second_name, email, birth_date, city FROM Matrons WHERE matron_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $matron_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $matron = $result->fetch_assoc();
    } else {
        $errorMessage = "Failed to fetch profile details. Please try again later.";
    }
}

// Update matron's details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $second_name = trim($_POST['second_name']);
    $email = trim($_POST['email']);
    $birth_date = trim($_POST['birth_date']);
    $city = trim($_POST['city']);
    $password = trim($_POST['password']);

    // Validate fields (basic validation)
    if (empty($first_name) || empty($second_name) || empty($email) || empty($birth_date) || empty($city)) {
        $errorMessage = "All fields except password are required.";
    } else {
        // Update query
        $query = "UPDATE Matrons SET first_name = ?, second_name = ?, email = ?, birth_date = ?, city = ?" . 
                 (!empty($password) ? ", password = ?" : "") . 
                 " WHERE matron_id = ?";
        $stmt = $conn->prepare($query);

        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hash the password
            $stmt->bind_param("ssssssi", $first_name, $second_name, $email, $birth_date, $city, $hashedPassword, $matron_id);
        } else {
            $stmt->bind_param("sssssi", $first_name, $second_name, $email, $birth_date, $city, $matron_id);
        }

        if ($stmt->execute()) {
            $successMessage = "Profile updated successfully!";
        } else {
            $errorMessage = "Failed to update profile. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="edit_matron_profile.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="form-container">
        <h2>Edit Profile</h2>
        <?php if ($successMessage): ?>
            <p class="success-message"><?php echo $successMessage; ?></p>
        <?php endif; ?>
        <?php if ($errorMessage): ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <form method="POST" action="edit_matron_profile.php">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($matron['first_name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="second_name">Last Name</label>
                <input type="text" id="second_name" name="second_name" value="<?php echo htmlspecialchars($matron['second_name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($matron['email'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="birth_date">Birth Date</label>
                <input type="date" id="birth_date" name="birth_date" value="<?php echo htmlspecialchars($matron['birth_date'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($matron['city'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">New Password (optional)</label>
                <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">
            </div>
            <button type="submit" class="btn">Update Profile</button>
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>
