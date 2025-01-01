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

// Fetch all contracts related to the logged-in supplier
$contractsQuery = "SELECT supplier_contract_id, contract_start_date, contract_end_date, contract_duration FROM supplier_contract WHERE supplier_id = ?";
$contractsStmt = $conn->prepare($contractsQuery);
$contractsStmt->bind_param("i", $supplier_id);
$contractsStmt->execute();
$contractsResult = $contractsStmt->get_result();

// Handle form submission for adding a new contract
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
        echo "<script>window.location.href='view_supplier_contract.php';</script>";
    } else {
        echo "<script>alert('Error: Could not add the contract.');</script>";
    }

    $addContractStmt->close();
}

// Handle contract deletion
if (isset($_GET['delete_contract'])) {
    $contract_id = (int)$_GET['delete_contract'];

    // Delete contract from the database
    $deleteContractSQL = "DELETE FROM supplier_contract WHERE supplier_contract_id = ?";
    $deleteContractStmt = $conn->prepare($deleteContractSQL);
    $deleteContractStmt->bind_param("i", $contract_id);

    if ($deleteContractStmt->execute()) {
        echo "<script>alert('Contract deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error: Could not delete the contract.');</script>";
    }

    $deleteContractStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Contracts</title>
    <style>
            table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }

    th {
        background-color: #4CAF50;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #ddd;
    }
        table tbody .edit-btn,
table tbody .delete-btn {
    background: #3faa44; /* Default background for edit button */
    color: #fff;
    display: inline-block;
    width: 100px; /* Set a fixed width for both buttons */
    text-align: center; /* Center align text */
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 12px;
}

table tbody .edit-btn:hover {
    background: #34b92d;
}

table tbody .delete-btn {
    background: #c93d3d;
}

table tbody .delete-btn:hover {
    background: #df3b3b;
}

        </style>
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

        <h2 style="margin-top:40px; margin-left:20px;">Your Contracts</h2>
        <div class="breadcrumbs">
            <a href="add_contract_details.php" class="breadcrumb-item">
                <i class="fas fa-plus"></i> Add Contract Details
            </a>
            <span class="breadcrumb-separator">|</span>
            <a href="new_supplier.php" class="breadcrumb-item">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </div>

        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top:20px;">
            <thead>
                <tr>
                    <th>Contract Start Year</th>
                    <th>Contract End Year</th>
                    <th>Contract Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($contract = $contractsResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($contract['contract_start_date']); ?></td>
                    <td><?php echo htmlspecialchars($contract['contract_end_date']); ?></td>
                    <td><?php echo htmlspecialchars($contract['contract_duration']); ?></td>
                    <td>
                        <a href="edit_contract.php?id=<?php echo $contract['supplier_contract_id']; ?>"class="edit-btn">Edit</a> |
                        <a href="?delete_contract=<?php echo $contract['supplier_contract_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this contract?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
