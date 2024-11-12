<?php
session_start();
if (!isset($_SESSION['matron_id'])) {
    header("Location: matron_auth.php");
    exit();
}

include 'db_connect.php';

$matron_id = $_SESSION['matron_id'];

// Retrieve recent transactions from the transactions table
$recentTransactionsQuery = "
SELECT trant_id, resident_id, trant_payment_receipt, trant_payment_date, trant_month, amount 
FROM transactionss
ORDER BY trant_payment_date DESC";

$recentTransactionsResult = $conn->query($recentTransactionsQuery);

// Check if the query failed
if (!$recentTransactionsResult) {
    die("Error retrieving transactions: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - Salvation Army Girls Hostel</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Recent Transactions</h1>
    <table>
        <thead>
            <tr>
                <th>Resident ID</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Month</th>
                <th>Receipt</th>
                
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $recentTransactionsResult->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['resident_id']); ?></td>
                <td>Rs. <?php echo number_format($row['amount'], 2); ?></td>
                <td><?php echo htmlspecialchars($row['trant_payment_date']); ?></td>
                <td><?php echo htmlspecialchars($row['trant_month']); ?></td>
                <td><a href="<?php echo htmlspecialchars($row['trant_payment_receipt']); ?>" target="_blank">View Receipt</a></td>
                
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Modal for viewing receipt -->
    <div id="receiptModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Transaction Receipt</h2>
            <div id="receiptDetails">Loading...</div>
        </div>
    </div>

    <script>
        function viewReceipt(trantId) {
            // Fetch receipt details via AJAX
            fetch(`fetch_receipt.php?trant_id=${trantId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("receiptDetails").innerHTML = data;
                    document.getElementById("receiptModal").style.display = "block";
                });
        }

        function closeModal() {
            document.getElementById("receiptModal").style.display = "none";
        }
    </script>

    <style>
        .modal { display: none; position: fixed; /* Add modal styling here */ }
        .modal-content { /* Style for modal content */ }
        .close { cursor: pointer; /* Style for close button */ }
    </style>
</body>
</html>

<?php $conn->close(); ?>
