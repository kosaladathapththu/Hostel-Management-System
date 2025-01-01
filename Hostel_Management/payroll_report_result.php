<?php
include 'db_connect.php';

if (!isset($_GET['report_type']) || !isset($_GET['report_period']) || empty($_GET['report_type']) || empty($_GET['report_period'])) {
    echo "<p>Error: Missing report type or period. Please go back and select both.</p>";
    echo '<a href="generate_payroll_reports.php">Back to Report Options</a>';
    exit();
}

$reportType = $_GET['report_type'];
$reportPeriod = $_GET['report_period'];

if ($reportType === 'monthly') {
    $query = "SELECT e.name, e.position, s.base_salary, s.allowances, s.deductions, s.total_salary, s.salary_date 
              FROM salary s 
              JOIN employees e ON s.employee_id = e.employee_id 
              WHERE DATE_FORMAT(s.salary_date, '%Y-%m') = ?";
} elseif ($reportType === 'annual') {
    $query = "SELECT e.name, e.position, SUM(s.base_salary) AS base_salary, 
              SUM(s.allowances) AS allowances, SUM(s.deductions) AS deductions, 
              SUM(s.total_salary) AS total_salary, YEAR(s.salary_date) AS salary_year 
              FROM salary s 
              JOIN employees e ON s.employee_id = e.employee_id 
              WHERE YEAR(s.salary_date) = ? 
              GROUP BY e.employee_id, salary_year";
}

$stmt = $conn->prepare($query);

if ($reportType === 'monthly') {
    $stmt->bind_param("s", $reportPeriod); // Format YYYY-MM for monthly
} else {
    $year = substr($reportPeriod, 0, 4); // Extract only the year part
    $stmt->bind_param("s", $year); // Format YYYY for annual
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Report</title>
    <link rel="stylesheet" href="payroll_report_result.css">
    <script>
        function printReport() {
            window.print();
        }
    </script>

<style>
        

        @media print {
            .btn-print {
                display: none; /* Hides the Print button on the print screen */
            }
        }
    
        @media print {
            .btn-print,
            .details-container, /* Hides detailed rows */
            th:nth-child(4), /* Hides the 'Details' header */
            td:nth-child(4) /* Hides the 'Details' column */ {
                display: none;
            }
        }
    </style>
</head>
<body>
    <header>
    <div class="header2">
            <img src="images/header.png" alt="Header Image">
        </div>
        <h1>Payroll Report - <?php echo ucfirst($reportType); ?></h1>
        <p>Report Period: <?php echo ($reportType === 'monthly') ? date("F Y", strtotime($reportPeriod)) : $year; ?></p>
    </header>
    <button class="print-btn" onclick="printReport()">Print Report</button>
    <section>
        <table>
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Position</th>
                    <th>Base Salary</th>
                    <th>Allowances</th>
                    <th>Deductions</th>
                    <th>Total Salary</th>
                    <?php if ($reportType === 'monthly'): ?>
                        <th>Salary Date</th>
                    <?php else: ?>
                        <th>Year</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['position']; ?></td>
                        <td><?php echo $row['base_salary']; ?></td>
                        <td><?php echo $row['allowances']; ?></td>
                        <td><?php echo $row['deductions']; ?></td>
                        <td><?php echo $row['total_salary']; ?></td>
                        <?php if ($reportType === 'monthly'): ?>
                            <td><?php echo $row['salary_date']; ?></td>
                        <?php else: ?>
                            <td><?php echo $row['salary_year']; ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <footer>
        <a href="generate_payroll_reports.php" class="back-link">Back to Report Options</a>
    </footer>
</body>

<!-- Footer Section -->
<div class="footer">
        <img src="images/footer.png" alt="Footer Image">
    </div>
    </div>
</html>

<?php
$stmt->close();
$conn->close();
?>
