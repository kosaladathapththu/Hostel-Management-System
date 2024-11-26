<?php
include 'db_connect.php'; // Include the database connection

// Fetch feedback along with resident names, meal names, comments, and ratings
$sql = "
    SELECT mf.feedback_id, r.resident_name AS resident_name, mp.meal_name, mf.comment, mf.rating, mf.submitted_at
    FROM meal_feedback mf
    JOIN residents r ON mf.resident_id = r.id
    JOIN meal_plans mp ON mf.meal_id = mp.meal_id
    ORDER BY mf.submitted_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <link rel="stylesheet" href="view_feedback.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <h1>Salvation Army Girls Hostel - Meal Feedbacks</h1>
    <div class="user-info">
        <p>Admin: [matron Name]</p>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<section class="feedback-list">
    <h2>Meal Plan Feedback</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Resident</th>
                    <th>Meal Plan</th>
                    <th>Comment</th>
                    <th>Rating</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['resident_name']; ?></td>
                        <td><?php echo $row['meal_name']; ?></td>
                        <td><?php echo $row['comment']; ?></td>
                        <td><?php echo $row['rating']; ?>/5</td>
                        <td><?php echo $row['submitted_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No feedback available.</p>
    <?php endif; ?>

    <!-- Add Meal Plan Button -->
    <div class="add-meal-btn">
        <a href="view_meal_plans.php" class="button">Meal Plans</a>
        <a href="dashboard.php" class="button">Dashboard</a>
    </div>
</section>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
