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


// Check if the contract ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid contract ID.";
    exit();
}

$contract_id = (int)$_GET['id'];
$supplier_id = $_SESSION['supplier_id'];

// Fetch the contract details from the database
$contractQuery = "SELECT supplier_contract_id, contract_start_date, contract_end_date, contract_duration FROM supplier_contract WHERE supplier_contract_id = ? AND supplier_id = ?";
$stmt = $conn->prepare($contractQuery);
$stmt->bind_param("ii", $contract_id, $supplier_id);
$stmt->execute();
$contractResult = $stmt->get_result();

// Check if contract exists
if ($contractResult->num_rows === 0) {
    echo "Contract not found.";
    exit();
}

$contract = $contractResult->fetch_assoc();

// Handle form submission for contract updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_contract'])) {
    $contract_start_year = (int)$_POST['contract_start_year'];
    $contract_end_year = (int)$_POST['contract_end_year'];
    $contract_duration = $contract_end_year - $contract_start_year;

    // Update the contract in the database
    $updateContractSQL = "UPDATE supplier_contract SET contract_start_date = ?, contract_end_date = ?, contract_duration = ? WHERE supplier_contract_id = ?";
    $updateContractStmt = $conn->prepare($updateContractSQL);
    $updateContractStmt->bind_param("iiii", $contract_start_year, $contract_end_year, $contract_duration, $contract_id);

    if ($updateContractStmt->execute()) {
        echo "<script>alert('Contract updated successfully!');</script>";
        echo "<script>window.location.href='view_supplier_contract.php';</script>";
    } else {
        echo "<script>alert('Error: Could not update the contract.');</script>";
    }

    $updateContractStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contract</title>
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

        <h2 style="margin-top:40px; margin-left:20px;">Update Contract</h2>
        <div class="breadcrumbs">
            <a href="view_supplier_contracts.php" class="breadcrumb-item">
                <i class="fas fa-bars"></i> View Contracts
            </a>
            <span class="breadcrumb-separator">|</span>
            <a href="new_supplier.php" class="breadcrumb-item">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </div>

        <form action="edit_contract.php?id=<?php echo $contract['supplier_contract_id']; ?>" method="POST">
            <div class="form-group">
                <label for="contract_start_year">Contract Start Year</label>
                <input type="number" name="contract_start_year" value="<?php echo $contract['contract_start_date']; ?>" required>
            </div>

            <div class="form-group">
                <label for="contract_end_year">Contract End Year</label>
                <input type="number" name="contract_end_year" value="<?php echo $contract['contract_end_date']; ?>" required>
            </div>

            <div class="form-group">
                <label for="contract_duration">Contract Duration (Years)</label>
                <input type="number" name="contract_duration" value="<?php echo $contract['contract_duration']; ?>" disabled>
            </div>

            <button type="submit" name="update_contract" class="submit-button">Update Contract</button>
        </form>
    </div>
</body>
</html>
