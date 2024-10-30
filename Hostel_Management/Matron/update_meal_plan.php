<?php
include 'db_connect.php'; // Include the database connection

// Check if the form is submitted
if (isset($_POST['update'])) {
    $meal_id = $_POST['meal_id'];
    $meal_name = $_POST['meal_name'];
    $description = $_POST['description'];
    $date = $_POST['date'];

    // Update the meal plan in the database
    $sql = "UPDATE meal_plans SET meal_name = ?, description = ?, date = ? WHERE meal_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $meal_name, $description, $date, $meal_id);

    if ($stmt->execute()) {
        echo "Meal plan updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch the meal plan details to update
if (isset($_GET['meal_id'])) {
    $meal_id = $_GET['meal_id'];

    $sql = "SELECT * FROM meal_plans WHERE meal_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $meal_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $meal = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Meal Plan</title>
    <link rel="stylesheet" href="update_meal_plan.css"> <!-- Link the CSS file -->
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

<center><h1>Update Meal Plan</h1>
<?php if (isset($meal)) { ?>
    <form method="POST" action="update_meal_plan.php">
        <input type="hidden" name="meal_id" value="<?php echo $meal['meal_id']; ?>">

        <label>Meal Name:</label>
        <input type="text" name="meal_name" value="<?php echo $meal['meal_name']; ?>" required><br>

        <label>Description:</label>
        <textarea name="description"><?php echo $meal['description']; ?></textarea><br>

        <label>Date:</label>
        <input type="date" name="date" value="<?php echo $meal['date']; ?>" required><br>

        <button type="submit" name="update">Update Meal Plan</button>

        <div class="meal_plans">
        <a href="view_feedback.php" class="button">View Feedback</a>
        <a href="dashboard.php" class="button">Dashboard</a>
        </div>

       

    </form><center>
<?php } else { ?>
    <p>Meal plan not found!</p>
<?php } ?>

</body>
</html>

