<?php
include 'db_connect.php'; // Include database connection

// Check if the report type (monthly or annually) is set
$reportType = isset($_GET['type']) ? $_GET['type'] : '';

if ($reportType == 'annually') {
    // Query for annual event report with event names
    $reportQuery = "
    SELECT YEAR(start_date) AS year, GROUP_CONCAT(CONCAT_WS(': ', DATE_FORMAT(start_date, '%Y-%m-%d'), title) SEPARATOR '<br>') AS event_list, COUNT(event_id) AS total_events 
    FROM Events 
    GROUP BY year 
    ORDER BY year";
} elseif ($reportType == 'monthly' && isset($_GET['year'])) {
    // Get the year for monthly reports
    $selectedYear = intval($_GET['year']);
    
    // Query for monthly event report for a specific year with event names and dates
    $reportQuery = "
    SELECT MONTH(start_date) AS month, GROUP_CONCAT(CONCAT_WS(': ', DATE_FORMAT(start_date, '%Y-%m-%d'), title) SEPARATOR '<br>') AS event_list, COUNT(event_id) AS total_events 
    FROM Events 
    WHERE YEAR(start_date) = $selectedYear 
    GROUP BY month 
    ORDER BY month";
} else {
    echo "Invalid report type or missing parameters.";
    exit;
}

$reportResult = $conn->query($reportQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Report</title>
    <link rel="stylesheet" href="generate_event_report.css"> <!-- Link to your CSS file -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - Event Reports</h1>
        <p>User: [Matron Name] <a href="logout.php" class="logout-button">Logout</a></p>
    </header>

    <section>
        <h2><?php echo ucfirst($reportType); ?> Event Report</h2>
        
        <!-- Beautiful table for event report -->
        <table class="report-table">
            <thead>
                <tr>
                    <th><?php echo ($reportType == 'annually') ? 'Year' : 'Month'; ?></th>
                    <th>Total Events</th>
                    <th>Event List</th> <!-- Add event list column -->
                </tr>
            </thead>
            <tbody>
                <?php while($row = $reportResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo ($reportType == 'annually') ? $row['year'] : $row['month']; ?></td>
                        <td><?php echo $row['total_events']; ?></td>
                        <td><?php echo $row['event_list']; ?></td> <!-- Display event names with dates -->
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Button container for report and print buttons -->
        <div class="button-container">
            <a href="generate_event_report.php?type=annually" class="report-button">Generate Annual Report</a>
            <a href="generate_event_report.php?type=monthly&year=2024" class="report-button">Generate Monthly Report for 2024</a>
            <button class="print-button" onclick="window.print()">Print Report</button> <!-- Print button --><br>
            <a href="dashboard.php" class="dashboard-button">Dashboard</a>
            <a href="view_calendar.php">Back to Calendar</a>
        </div>
    </section>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
