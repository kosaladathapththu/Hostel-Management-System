<?php
include 'db_connect.php'; // Include the database connection

// Get the current month
$currentMonth = date('Y-m');

// Query to fetch meal plans for the current month
$sql = "SELECT * FROM meal_plans WHERE DATE_FORMAT(created_at, '%Y-%m') = '$currentMonth'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="monthly_report_meal.css">
    <title>Monthly Meal Plan Report</title>
    <script>
        function printReport() {
            window.print();
        }
    </script>
</head>
<body>
    <h1>Monthly Meal Plan Report</h1>
    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Meal Name</th>
                    <th>Description</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['meal_name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No meal plans for this month.</p>
    <?php endif; ?>

    <button onclick="printReport()">Print Report</button>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
