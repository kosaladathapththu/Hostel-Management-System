<?php
include 'db_connect.php'; // Include database connection

// Get the report type from the GET request
$reportType = isset($_GET['report_type']) ? $_GET['report_type'] : '';

// Set the date range based on report type
if ($reportType === 'monthly') {
    $startDate = date("Y-m-01"); // First day of current month
    $endDate = date("Y-m-t"); // Last day of current month
} elseif ($reportType === 'yearly') {
    $startDate = date("Y-01-01"); // First day of current year
    $endDate = date("Y-12-31"); // Last day of current year
} else {
    echo "Invalid report type selected.";
    exit();
}

// Check database connection
if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch inventory data within the specified date range
$query = "
    SELECT item_id, item_name, category, quantity, item_price, last_updated 
    FROM inventory 
    WHERE last_updated BETWEEN ? AND ?
    ORDER BY category";
$stmt = $conn->prepare($query);

// Check if the query was prepared successfully
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

// Bind parameters and execute query
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo ucfirst($reportType); ?> Inventory Report</title>
    <link rel="stylesheet" href="report.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .actions {
            margin: 20px 0;
        }
        .button {
            padding: 10px 20px;
            margin-right: 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <h1><?php echo ucfirst($reportType); ?> Inventory Report</h1>
        <p>Report Period: <?php echo $startDate; ?> to <?php echo $endDate; ?></p>
    </header>

    <section>
        <table>
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['item_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($row['category'] ?: 'N/A')); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td><?php echo number_format($row['item_price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($row['last_updated']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="actions">
            <!-- Download as CSV Button -->
            <form method="GET" action="generate_report_enventory.php" style="display: inline;">
                <input type="hidden" name="report_type" value="<?php echo $reportType; ?>">
                <button type="submit" name="download" value="csv" class="button">Download as CSV</button>
            </form>

            <!-- Print Button -->
            <button class="button" onclick="window.print()">Print Report</button>
        </div>
    </section>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
