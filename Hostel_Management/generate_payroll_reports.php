<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Payroll Reports</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Generate Payroll Report</h1>
    </header>
    <section>
        <form action="payroll_report_result.php" method="GET">
            <label for="report_type">Select Report Type:</label>
            <select name="report_type" id="report_type" required>
                <option value="">-- Select Report Type --</option>
                <option value="monthly">Monthly</option>
                <option value="annual">Annual</option>
            </select>

            <label for="report_period">Select Period:</label>
            <input type="month" name="report_period" id="report_period" required>

            <button type="submit">Generate Report</button>
        </form>
    </section>
</body>
</html>
