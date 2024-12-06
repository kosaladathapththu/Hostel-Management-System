<?php
require_once 'db_connect.php';

if (isset($_GET['id'])) {
    $transaction_id = intval($_GET['id']);
    
    $stmt = $conn->prepare(
        "SELECT transactionss.*, residents.resident_name 
         FROM transactionss 
         JOIN residents ON transactionss.resident_id = residents.id 
         WHERE transactionss.trant_id = ?"
    );
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $transaction = $result->fetch_assoc();

    if ($transaction) {
        echo "<h1>Payment Details</h1>";
        echo "<p><strong>Resident Name:</strong> " . htmlspecialchars($transaction['resident_name']) . "</p>";
        echo "<p><strong>Payment Date:</strong> " . htmlspecialchars($transaction['trant_payment_date']) . "</p>";
        echo "<p><strong>Month:</strong> " . htmlspecialchars($transaction['trant_month']) . "</p>";
        echo "<p><strong>Amount:</strong> Rs." . number_format($transaction['amount'], 2) . "</p>";
        echo "<p><strong>Receipt:</strong> <a href='" . htmlspecialchars($transaction['trant_payment_receipt']) . "' target='_blank'>View Receipt</a></p>";
    } else {
        echo "<p>Transaction not found.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>
