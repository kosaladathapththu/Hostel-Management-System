<?php
include 'db_connect.php';
session_start();

// Ensure the supplier is logged in
if (!isset($_SESSION['supplier_id'])) {
    header("Location: supplier_login.php");
    exit();
}

$supplier_id = $_SESSION['supplier_id'];

// Fetch supplier's information
$supplierQuery = "SELECT username FROM suppliers WHERE supplier_id = ?";
$stmt = $conn->prepare($supplierQuery);
$stmt->bind_param("i", $supplier_id);
$stmt->execute();
$supplierResult = $stmt->get_result();
$supplier = $supplierResult->fetch_assoc();

// Handle form submission for supplier contracts
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_contract'])) {
    $contract_start_year = (int)$_POST['contract_start_year'];
    $contract_end_year = (int)$_POST['contract_end_year'];
    $contract_duration = $contract_end_year - $contract_start_year;

    // Insert new contract details into the database
    $addContractSQL = "INSERT INTO supplier_contract (contract_start_date, contract_end_date, supplier_id, contract_duration) 
                        VALUES (?, ?, ?, ?)";
    $addContractStmt = $conn->prepare($addContractSQL);

    // Check if the statement prepared successfully
    if (!$addContractStmt) {
        die("Error preparing statement for contract insertion: " . $conn->error);
    }

    // Bind parameters
    $addContractStmt->bind_param("iiii", $contract_start_year, $contract_end_year, $supplier_id, $contract_duration);

    // Execute the query to insert the new contract
    if ($addContractStmt->execute()) {
        echo "<script>alert('Contract added successfully!');</script>";
        echo "<script>window.location.href='new_supplier.php';</script>";
    } else {
        echo "<script>alert('Error: Could not add the contract.');</script>";
    }

    $addContractStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Contracts</title>
    <link rel="stylesheet" href="new_supplier.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Supplier Panel</h2>
        <div class="profile-info">
            <p>Welcome, <?php echo htmlspecialchars($supplier['username']); ?></p>
        </div>
        <ul>
        <li><a href="new_supplier.php"><i class="fas fa-home"></i> Dashboard</a></li><br>
            <li><a href="supplier_dashboard.php"><i class="fas fa-users"></i> Order Management</a></li><br>
            <li><a href="view_supplier_contracts.php"><i class="fas fa-bars"></i> Supplier Contracts</a></li><br>

            <button onclick="window.location.href='edit_supplier_profile.php'" class="edit-btn"><i class="fas fa-user-edit"></i> Edit Profile</button>
            <button onclick="window.location.href='supplier_logout.php'" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div class="header-left">
                <img src="The_Salvation_Army.png" alt="Logo" class="logo">
            </div>
            <center><b><h1>Salvation Army Girls Hostel</h1></b></center>
            <div class="header-right">
                <p>Welcome, <?php echo htmlspecialchars($supplier['username']); ?></p>
            </div>
        </header>



        <h2 style="margin-left:20px; margin-top:20px;">Add New Contract</h2>
        <div class="breadcrumbs">
                <a href="view_supplier_contracts.php" class="breadcrumb-item">
                    <i class="fas fa-backward"></i>Back to Contract Details
                </a>

                <span class="breadcrumb-separator">|</span>
                <a href="new_supplier.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>


        <form method="POST" action="">
            <label for="contract_start_year">Contract Start Year:</label><br>
            <input type="date" name="contract_start_year" id="contract_start_year" required placeholder="Enter start year">
            <br>
            <label for="contract_end_year">Contract End Year:</label><br>
            <input type="date" name="contract_end_year" id="contract_end_year" required placeholder="Enter end year" oninput="calculateDuration()">
            <br>
            <label for="contract_duration">Contract Duration:</label><br>
            <input type="number" name="contract_duration" id="contract_duration" readonly placeholder="Duration will auto-calculate">
            <br><br>
            <input type="submit" name="add_contract" value="Add Contract" class="submit-button">
        </form>

        <script>
            function calculateDuration() {
                const startYear = parseInt(document.getElementById('contract_start_year').value) || 0;
                const endYear = parseInt(document.getElementById('contract_end_year').value) || 0;
                const duration = endYear - startYear;
                document.getElementById('contract_duration').value = duration > 0 ? duration : 0;
            }
        </script>

    </div>
</body>
</html>
