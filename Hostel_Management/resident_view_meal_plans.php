<?php
include 'db_connect.php'; // Include the database connection

// Fetch meal plans from the database
$sql = "SELECT meal_id, meal_name, description, date, created_by, created_at FROM meal_plans ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Meal Plans</title>
    <link rel="stylesheet" href="view_meal_plans.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <h1>Salvation Army Girls Hostel - Meal Plans</h1>
    <div class="user-info">
        
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<section class="meal-plans-list">
    <h2>Meal Plans</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Meal ID</th>
                    <th>Meal Name</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Created By</th>
                    <th>Created At</th>
                   
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['meal_id']; ?></td>
                        <td><?php echo $row['meal_name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['created_by']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No meal plans available.</p>
    <?php endif; ?>

    <!-- Add Meal Plan Button -->
    <div class="add-meal-btn">
        <a href="submit_feedback.php" class="button">Submit Feedback</a>
        <a href="resident_dashboard.php" class="button">Dashboard</a>
    </div>
</section>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
