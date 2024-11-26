<?php
session_start();
require_once 'db_connect.php';

class TransactionManager {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function handleTransaction() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $resident_id = $_POST['resident_id'];
            $trant_payment_date = $_POST['trant_payment_date'];
            $t  rant_month = $_POST['trant_month'];
            $amount = $_POST['amount'];
                               
            if ($this->uploadReceipt()) {
                return $this->saveTransaction($resident_id, $trant_payment_date, $trant_month, $amount);
            }
            return false;
        }
        return null;
    }
    
    private function uploadReceipt() {
        if (isset($_FILES['trant_payment_receipt']) && $_FILES['trant_payment_receipt']['error'] == 0) {
            $file_tmp_name = $_FILES['trant_payment_receipt']['tmp_name'];
            $file_name = $_FILES['trant_payment_receipt']['name'];
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($file_name);
            
            return move_uploaded_file($file_tmp_name, $target_file);
        }
        return false;
    }
    
    private function saveTransaction($resident_id, $trant_payment_date, $trant_month, $amount) {
        $target_file = "uploads/" . basename($_FILES['trant_payment_receipt']['name']);
        $sql = "INSERT INTO transactionss (resident_id, trant_payment_receipt, trant_payment_date, trant_month, amount)
                VALUES (?, ?, ?, ?, ?)";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isssd", $resident_id, $target_file, $trant_payment_date, $trant_month, $amount);
        return $stmt->execute();
    }
    
    public function getResidentData($resident_id) {
        $stmt = $this->conn->prepare("SELECT resident_name FROM residents WHERE id = ?");
        $stmt->bind_param("i", $resident_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function getAllTransactions() {
        return $this->conn->query(
            "SELECT transactionss.*, residents.name 
             FROM transactionss 
             JOIN residents ON transactionss.resident_id = residents.id
             ORDER BY trant_payment_date DESC"
        );
    }
}

// Initialize manager
$transactionManager = new TransactionManager($conn);

// Handle form submission
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($transactionManager->handleTransaction()) {
        $message = "<div class='alert success'>Transaction added successfully.</div>";
    } else {
        $message = "<div class='alert error'>Error processing transaction.</div>";
    }
}

// Get resident data
$resident_id = 1; // Replace with actual session logic
$resident_data = $transactionManager->getResidentData($resident_id);
$resident_name = $resident_data['resident_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction </title>
    <link rel="stylesheet" href="transaction.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header>
            <h1>Transaction Management</h1>
        </header>

        <?php echo $message; ?>

        <div class="card">
            <h2>Add New Transaction</h2>
            <form method="POST" action="transaction.php" enctype="multipart/form-data" class="transaction-form">
                <div class="form-group">
                    <label for="resident_id">Resident ID:</label>
                    <input type="text" name="resident_id" value="<?php echo $resident_id; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="resident_name">Resident Name:</label>
                    <input type="text" name="resident_name" value="<?php echo $resident_name; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="trant_payment_receipt">Payment Receipt:</label>
                    <input type="file" name="trant_payment_receipt" required>
                </div>

                <div class="form-group">
                    <label for="trant_payment_date">Payment Date:</label>
                    <input type="date" name="trant_payment_date" required>
                </div>

                <div class="form-group">
                    <label for="trant_month">Month:</label>
                    <input type="month" name="trant_month" required>
                </div>

                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="number" step="0.01" name="amount" placeholder="Enter amount" required>
                </div>

                <button type="submit" class="btn-submit">Submit Transaction</button>
            </form>
        </div>

        <div class="card">
            <h2>Transaction History</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Resident</th>
                            <th>Receipt</th>
                            <th>Date</th>
                            <th>Month</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $transactions = $transactionManager->getAllTransactions();
                        while ($row = $transactions->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['trant_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td><a href='" . htmlspecialchars($row['trant_payment_receipt']) . "' class='btn-view' target='_blank'>View</a></td>";
                            echo "<td>" . htmlspecialchars($row['trant_payment_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['trant_month']) . "</td>";
                            echo "<td>Rs." . number_format($row['amount'], 2) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>