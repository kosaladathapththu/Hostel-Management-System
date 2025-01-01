<?php
include 'db_connect.php';

if (isset($_GET['bill_id'])) {
    $bill_id = $_GET['bill_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $amount_given = $_POST['amount_given'];

        // Update the Bill table
        $query = "UPDATE Bill SET amount_given = ? WHERE bill_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("di", $amount_given, $bill_id);
        if ($stmt->execute()) {
            // Update bill_status
            $updateStatusQuery = "
                UPDATE Bill 
                SET bill_status = CASE 
                    WHEN bill_amount = amount_given THEN 'fully_paid'
                    ELSE 'half_paid'
                END
                WHERE bill_id = ?";
            $statusStmt = $conn->prepare($updateStatusQuery);
            $statusStmt->bind_param("i", $bill_id);
            $statusStmt->execute();

            echo "<script>alert('Payment updated successfully.');</script>";
        } else {
            echo "<script>alert('Error updating payment.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
</head>
<body>
    <h1>Matron Payment</h1>
    <form method="POST" action="">
        <label for="amount_given">Amount Given:</label>
        <input type="number" name="amount_given" step="0.01" required>
        <br>
        <input type="submit" value="Submit Payment">
    </form>
</body>
</html>
