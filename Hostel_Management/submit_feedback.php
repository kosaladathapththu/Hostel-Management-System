<?php
include 'db_connect.php'; // Include the database connection

$message = ""; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resident_id = $_POST['resident_id'];
    $meal_id = $_POST['meal_id'];
    $comment = $_POST['comment'];
    $rating = $_POST['rating'];

    $sql = "INSERT INTO meal_feedback (resident_id, meal_id, comment, rating) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $resident_id, $meal_id, $comment, $rating);

    if ($stmt->execute()) {
        $message = "Feedback submitted successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Feedback</title>
    <link rel="stylesheet" href="submit_feedback.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<header>
        <h1>Salvation Army Girls Hostel - Dashboard</h1>
        <div class="user-info">
            <p>Admin: [matron Name]</p>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <!-- Success/Error Message Display -->
    <?php if (!empty($message)) : ?>
        <div class="notification" id="notification"><?php echo $message; ?></div>
    <?php endif; ?>

    <center><form method="POST">
        <label>Meal Plan ID:</label>
        <input type="number" name="meal_id" required><br>

        <label>Resident ID:</label>
        <input type="number" name="resident_id" required><br>

        <label>Comment:</label>
        <textarea name="comment" required></textarea><br>

        <label>Rating (1-5):</label>
        <input type="number" name="rating" min="1" max="5" required><br>

        <button type="submit">Submit Feedback</button>

        <!-- Navigation Buttons -->
        <div class="nav-buttons">
            <a href="resident_dashboard.php" class="button">Resident Dashboard</a>
            <a href="resident_view_meal_plans.php" class="button">View Meal Plans</a>
        </div>
    </form></center>

    <script>
        // JavaScript to hide notification after 3 seconds
        window.onload = function() {
            const notification = document.getElementById('notification');
            if (notification) {
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 3000);
            }
        };
    </script>
</body>
</html>

