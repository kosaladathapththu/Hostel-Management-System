<?php
include 'db_connect.php';

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

    if (isset($_GET['download']) && $_GET['download'] === 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="' . $reportType . '_report.csv"');
        
        $output = fopen("php://output", "w");
        fputcsv($output, ['Item ID', 'Item Name', 'Category', 'Quantity', 'Last Updated']);
        
        // Fetch and write data to CSV
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit();
    }

}

// Fetch inventory data within the specified date range
$query = "
    SELECT item_id, item_name, category, quantity, last_updated 
    FROM Inventory 
    WHERE last_updated BETWEEN ? AND ?
    ORDER BY category";
$stmt = $conn->prepare($query);
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
</head>
<body>
    <header>
        <h1><?php echo ucfirst($reportType); ?> Inventory Report</h1>
        <p>Report Period: <?php echo $startDate; ?> to <?php echo $endDate; ?></p>
    </header>

    <section>
        <table class="inventory-report">
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['item_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($row['category'])); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['last_updated']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <form method="GET" action="generate_report_enventory.php">
            <input type="hidden" name="report_type" value="<?php echo $reportType; ?>">
            <button type="submit" name="download" value="csv">Download as CSV</button>
            </form>

    </section>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
