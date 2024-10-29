<?php
include 'db_connect.php'; // Include the database connection

// Check if 'meal_id' is provided via GET request to delete a specific meal plan
if (isset($_GET['meal_id'])) {
    $meal_id = $_GET['meal_id'];

    // SQL query to delete the meal plan
    $sql = "DELETE FROM meal_plans WHERE meal_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $meal_id);

    if ($stmt->execute()) {
        echo "Meal plan deleted successfully!";
        header("Location: view_meal_plans.php"); // Redirect to the meal plans list page
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "No meal plan selected to delete!";
}

if (!isset($_GET['meal_id'])) {
    header("Location: view_meal_plans.php"); // Redirect if accessed without a meal_id
    exit();
}


?>

