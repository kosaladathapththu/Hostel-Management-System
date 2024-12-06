<?php
session_start();
if (!isset($_SESSION['matron_id'])) {
    header("Location: matron_auth.php");
    exit();
}

include 'db_connect.php';

// Fetch all transactions grouped by month
$transactionsQuery = "
    SELECT trant_month, SUM(amount) AS monthly_total, COUNT(*) AS transaction_count
    FROM transactionss
    GROUP BY trant_month
    ORDER BY trant_month DESC";

$transactionsResult = $conn->query($transactionsQuery);

// Calculate grand total
$grandTotalQuery = "SELECT SUM(amount) AS grand_total FROM transactionss";
$grandTotalResult = $conn->query($grandTotalQuery);
$grandTotal = $grandTotalResult->fetch_assoc()['grand_total'] ?? 0;

// Fetch all individual transactions
$allTransactionsQuery = "
    SELECT trant_month, resident_id, trant_payment_date, amount
    FROM transactionss
    ORDER BY trant_month, trant_payment_date";

$allTransactionsResult = $conn->query($allTransactionsQuery);

// Organize transactions by month
$transactionsByMonth = [];
while ($row = $allTransactionsResult->fetch_assoc()) {
    $month = $row['trant_month'];
    if (!isset($transactionsByMonth[$month])) {
        $transactionsByMonth[$month] = [];
    }
    $transactionsByMonth[$month][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Report</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container { margin: 20px; font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .transaction-table, .details-table, .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .transaction-table th, .transaction-table td, .summary-table th, .summary-table td, .details-table th, .details-table td { 
            border: 1px solid #ddd; padding: 8px; 
        }
        .transaction-table th, .summary-table th, .details-table th { background-color: #f4f4f4; text-align: left; }
        .btn-toggle { background-color: #007BFF; color: white; padding: 5px 10px; border: none; cursor: pointer; }
        .btn-toggle:hover { background-color: #0056b3; }
        .btn-print { background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; margin-top: 20px; }
        .btn-print:hover { background-color: #45a049; }
        .details-container { display: none; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Transaction Report</h1>
            <p>Salvation Army Girls Hostel</p>
        </div>

        <h2>Monthly Summary</h2>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Total Transactions</th>
                    <th>Total Amount (Rs.)</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactionsByMonth as $month => $transactions): 
                    $monthlyTotal = array_sum(array_column($transactions, 'amount'));
                    $transactionCount = count($transactions);
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($month); ?></td>
                    <td><?php echo $transactionCount; ?></td>
                    <td>Rs. <?php echo number_format($monthlyTotal, 2); ?></td>
                    <td>
                        <button class="btn-toggle" onclick="toggleDetails('<?php echo $month; ?>')">View Details</button>
                    </td>
                </tr>
                <tr class="details-container" id="details-<?php echo $month; ?>">
                    <td colspan="4">
                        <h3>Details for <?php echo htmlspecialchars($month); ?></h3>
                        <table class="details-table">
                            <thead>
                                <tr>
                                    <th>Resident ID</th>
                                    <th>Payment Date</th>
                                    <th>Amount (Rs.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($transaction['resident_id']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['trant_payment_date']); ?></td>
                                    <td>Rs. <?php echo number_format($transaction['amount'], 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Grand Total</h2>
        <p><strong>Total Amount:</strong> Rs. <?php echo number_format($grandTotal, 2); ?></p>

        <button class="btn-print" onclick="window.print()">Print Report</button>
    </div>

    <script>
        function toggleDetails(month) {
            const details = document.getElementById('details-' + month);
            if (details.style.display === 'none' || details.style.display === '') {
                details.style.display = 'table-row';
            } else {
                details.style.display = 'none';
            }
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>
