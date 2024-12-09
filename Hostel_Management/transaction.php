<?php
session_start();
require_once 'db_connect.php';

class TransactionManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function handleTransaction() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $resident_id = $_POST['resident_id'];
            $trant_payment_date = $_POST['trant_payment_date'];
            $trant_month = $_POST['trant_month'];
            $amount = $_POST['amount'];

            $receipt_path = $this->uploadReceipt();
            if ($receipt_path) {
                return $this->saveTransaction($resident_id, $receipt_path, $trant_payment_date, $trant_month, $amount);
            }
            return false;
        }
        return null;
    }

    private function uploadReceipt() {
        if (isset($_FILES['trant_payment_receipt']) && $_FILES['trant_payment_receipt']['error'] === 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
            $file_type = mime_content_type($_FILES['trant_payment_receipt']['tmp_name']);
            $file_tmp_name = $_FILES['trant_payment_receipt']['tmp_name'];
            $file_name = uniqid('receipt_', true) . '_' . basename($_FILES['trant_payment_receipt']['name']);
            $target_dir = "uploads/";
            $target_file = $target_dir . $file_name;

            if (in_array($file_type, $allowed_types)) {
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }
                if (move_uploaded_file($file_tmp_name, $target_file)) {
                    return $target_file;
                }
            }
        }
        return false;
    }

    private function saveTransaction($resident_id, $receipt_path, $trant_payment_date, $trant_month, $amount) {
        $sql = "INSERT INTO transactionss (resident_id, trant_payment_receipt, trant_payment_date, trant_month, amount)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("isssd", $resident_id, $receipt_path, $trant_payment_date, $trant_month, $amount);
            return $stmt->execute();
        }
        return false;
    }

    public function getResidentData($resident_id) {
        $stmt = $this->conn->prepare("SELECT resident_name FROM residents WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $resident_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return null;
    }

    public function getAllTransactions($resident_id) {
        $stmt = $this->conn->prepare(
            "SELECT transactionss.*, residents.resident_name 
             FROM transactionss 
             JOIN residents ON transactionss.resident_id = residents.id
             WHERE transactionss.resident_id = ?
             ORDER BY trant_payment_date DESC"
        );
        $stmt->bind_param("i", $resident_id);
        $stmt->execute();
        return $stmt->get_result();
    }
}

// Initialize manager
$transactionManager = new TransactionManager($conn);

// Handle form submission
$message = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($transactionManager->handleTransaction()) {
        $message = "<div class='alert success'>Transaction added successfully.</div>";
    } else {
        $message = "<div class='alert error'>Error processing transaction. Ensure all fields are correctly filled.</div>";
    }
}

// Get resident data from session (update to actual session logic)
$resident_id = $_SESSION['resident_id'] ?? 1; // Example session ID
$resident_data = $transactionManager->getResidentData($resident_id);
$resident_name = $resident_data['resident_name'] ?? 'Unknown';

// Get only this resident's transactions
$transactions = $transactionManager->getAllTransactions($resident_id);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Management</title>
    <link rel="stylesheet" href="transaction.css">
    <link rel="stylesheet" href="view_meal_plans.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    .btn-print {
    background-color: #4CAF50;
    color: white;
    padding: 8px 12px;
    border: none;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.btn-print:hover {
    background-color: #45a049;
}</style>
</head>
<body>
            <!-- Sidebar -->
            <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Resident Panel</h2>
        <ul>
                    <li class="active">
                        <a href="resident_dashboard.php"><i class="fas fa-home"></i>Dashboard</a>
                    </li>
                    <li>
                        <a href="edit_profile.php"><i class="fas fa-user"></i>Profile</a>
                    </li>
                    <li>
                        <a href="resident_view_meal_plans.php"><i class="fas fa-utensils"></i>Meals</a>
                    </li>
                    <li>
                        <a href="update_checkin_checkout.php"><i class="fas fa-calendar-check"></i>Check-in/out</a>
                    </li>
                    <li>
                        <a href="Re_view_calendar.php"><i class="fas fa-calendar"></i>Events</a>
                    </li>
                    <li>
                        <a href="transaction.php"><i class="fa fa-credit-card"></i>Monthly Fee</a>
                    </li>
                    <li>
                        <a href="#support"><i class="fas fa-headset"></i>Support</a>
                    </li>
                </ul>
                <button onclick="window.location.href='edit_profile.php'" class="edit-btn"><i class="fas fa-user-edit"></i> Edit Profile</button>
                <button onclick="window.location.href='login.php'" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div class="header-left">
                <img src="The_Salvation_Army.png" alt="Logo" class="logo">
            </div>
            <center><b><h2 style="text-align:left; margin-right: 450px;margin-top :20px;color:black;">Salvation Army Girls Hostel</h2></b></center>
        </header>

        <section class="meal-plans-list">
        <h2>Confirm Transaction</h2>
            <div class="breadcrumbs">

    <span class="breadcrumb-separator">|</span>
    <a href="resident_dashboard.php" class="breadcrumb-item">
        <i class="fas fa-home"></i> Resident Dashboard
    </a>

</div>
    <div class="container">


        <?php echo $message; ?>

        <div class="card">
            
            <form method="POST" action="transaction.php" enctype="multipart/form-data" class="transaction-form">
                <div class="form-group">
                    <label for="resident_id">Resident ID:</label>
                    <input type="text" name="resident_id" value="<?php echo htmlspecialchars($resident_id); ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="resident_name">Resident Name:</label>
                    <input type="text" name="resident_name" value="<?php echo htmlspecialchars($resident_name); ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="trant_payment_receipt">Payment Receipt:</label>
                    <input type="file" name="trant_payment_receipt" accept=".jpg,.jpeg,.png,.pdf" required>
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
        <th>Action</th> <!-- New Column for Print -->
    </tr>
</thead>
<tbody>
    <?php
    while ($row = $transactions->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['trant_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['resident_name']) . "</td>";
        echo "<td><a href='" . htmlspecialchars($row['trant_payment_receipt']) . "' class='btn-view' target='_blank'>View</a></td>";
        echo "<td>" . htmlspecialchars($row['trant_payment_date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['trant_month']) . "</td>";
        echo "<td>Rs." . number_format($row['amount'], 2) . "</td>";
        echo "<td><button onclick='printPayment(" . $row['trant_id'] . ")' class='btn-print'>Print</button></td>";
        echo "</tr>";
    }
    ?>
</tbody>


        </table>
    </div>
</div>

    </div>
    
    <script>
function printPayment(transactionId) {
    // Fetch transaction details using AJAX
    fetch(`print_payment.php?id=${transactionId}`)
        .then(response => response.text())
        .then(data => {
            // Open a new print window
            const printWindow = window.open('', '_blank');
            printWindow.document.write(data);
            printWindow.document.close();
            printWindow.print();
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
</script>


</body>
</html>
