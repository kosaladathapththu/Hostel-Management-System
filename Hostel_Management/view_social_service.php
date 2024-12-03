<?php
include 'db_connect.php'; // Database connection

$query = "SELECT service_date, service_province, service_city, service_street, service_description FROM social_service ORDER BY service_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Social Services</title>
    <link rel="stylesheet" href="view_social_service.css">
</head>
<body>
    <div class="services-container">
        <h1>Social Services</h1>
        <?php if ($result->num_rows > 0): ?>
            <div class="services-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="service-box">
                        <p><strong>Date:</strong> <?php echo $row['service_date']; ?></p>
                        <p><strong>Province:</strong> <?php echo $row['service_province']; ?></p>
                        <p><strong>City:</strong> <?php echo $row['service_city']; ?></p>
                        <p><strong>Street:</strong> <?php echo $row['service_street']; ?></p>
                        <p><strong>Description:</strong> <?php echo $row['service_description']; ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No social services found.</p>
        <?php endif; ?>
    </div>
</body>
</html>