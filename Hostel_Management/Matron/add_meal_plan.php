<?php
include 'db_connect.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $meal_name = $_POST['meal_name'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $created_by = 'Matron'; // Can be dynamic if logged-in matron's name is available

    $sql = "INSERT INTO meal_plans (meal_name, description, date, created_by) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $meal_name, $description, $date, $created_by);

    if ($stmt->execute()) {
        echo "Meal plan added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Meal Plan</title>
    <link rel="stylesheet" href="add_meal_plan.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header Section -->
<header>
        <h1>Salvation Army Girls Hostel - Dashboard</h1>
        <div class="user-info">
            <p>Admin: [matron Name]</p>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

<!-- Main Content Section -->
<center><div class="main-content">
  <!-- Form Section -->
  <div class="container">
    <h2>Add Meal Plan</h2>
    <form action="" method="POST">
      <label for="meal_name">Meal Name:</label>
      <input type="text" id="meal_name" name="meal_name" placeholder="Enter Meal Name" required>

      <label for="description">Description:</label>
      <textarea id="description" name="description" placeholder="Enter Description" required></textarea>

      <label for="date">Date:</label>
      <input type="date" id="date" name="date" required>

      <button type="submit" class="submit-btn">Add Meal Plan</button>
    </form>
  </div>

  <!-- Dashboard Button Section -->
  <div class="dashboard-btn">
    <a href="dashboard.php">Go to Dashboard</a>
  </div>
</div></center>

</body>
</html>
