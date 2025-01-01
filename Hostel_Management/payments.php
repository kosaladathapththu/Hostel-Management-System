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
    SELECT trant_month, resident_id, trant_payment_date, amount, trant_payment_receipt
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
    <link rel="stylesheet" href="payments.css">

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
    <!-- Header Section -->
    <div class="header">
        <img src="images/header.png" alt="Header Image">
    </div>

    <!-- Content Section -->
    <div class="container">
        <center><h2>Transaction Report</h2></center>
       


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
                                    <th>Receipt</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($transaction['resident_id']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['trant_payment_date']); ?></td>
                                    <td>Rs. <?php echo number_format($transaction['amount'], 2); ?></td>
                                    <td>
                                        <?php if (!empty($transaction['trant_payment_receipt'])): ?>
                                            <a href="<?php echo htmlspecialchars($transaction['trant_payment_receipt']); ?>" target="_blank">View Receipt</a>
                                        <?php else: ?>
                                            No Receipt
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Net Salary</h2>
        <p><strong>Total Amount:</strong> Rs. <?php echo number_format($grandTotal, 2); ?></p>

        <button class="btn-print" onclick="window.print()" >Print Report</button>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        <img src="images/footer.png" alt="Footer Image">
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
