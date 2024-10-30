<?php
include 'db_connect.php'; // Include database connection

// Check if the report type is set (monthly or annually)
$reportType = isset($_GET['type']) ? $_GET['type'] : '';

if ($reportType == 'monthly') {
    // Monthly report query
    $reportQuery = "
    SELECT MONTH(payment_date) AS month, SUM(amount) AS total_amount
    FROM Payments
    WHERE YEAR(payment_date) = YEAR(CURDATE())
    GROUP BY month";
} elseif ($reportType == 'annually') {
    // Annual report query
    $reportQuery = "
    SELECT YEAR(payment_date) AS year, SUM(amount) AS total_amount
    FROM Payments
    GROUP BY year";
} else {
    echo "Invalid report type.";
    exit;
}

$reportResult = $conn->query($reportQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo ucfirst($reportType); ?> Bill Report</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="report-container">
        <header>
            <h1>Bill Report</h1>
            <h2><?php echo ucfirst($reportType); ?> Report</h2>
            <p>Date: <?php echo date('Y-m-d'); ?></p>
        </header>

        <table>
            <thead>
                <tr>
                    <th><?php echo $reportType == 'monthly' ? 'Month' : 'Year'; ?></th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $reportResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $reportType == 'monthly' ? $row['month'] : $row['year']; ?></td>
                        <td><?php echo $row['total_amount']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <footer>
            <p>Thank you for reviewing the report.</p>
            <button onclick="window.print()">Print Report</button>
        </footer>
    </div>

    <a href="view_orders.php">Back to Orders</a>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
