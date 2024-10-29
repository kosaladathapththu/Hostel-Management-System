<?php
include 'db_connect.php'; // Include database connection

// Fetch all suppliers
$suppliersQuery = "SELECT * FROM Suppliers";
$suppliersResult = $conn->query($suppliersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Suppliers</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> <!-- Google Fonts -->
    <link rel="stylesheet" href="supplieradd.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - View Suppliers</h1>
        <p>User: [Matron Name] <a href="logout.php">Logout</a></p>
    </header>

    <section>
        <h2>Supplier List</h2>
        <table>
            <thead>
                <tr>
                    <th>Supplier ID</th>
                    <th>Supplier Name</th>
                    <th>Category</th>
                    <th>Contact</th>
                    <th>Actions</th>
                    <th>Rate Supplier</th> <!-- New header for Rate Supplier -->
                </tr>
            </thead>
            <tbody>
                <?php while($row = $suppliersResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['supplier_id']; ?></td>
                        <td><?php echo $row['supplier_name']; ?></td>
                        <td><?php echo $row['category']; ?></td>
                        <td><?php echo $row['contact']; ?></td>
                        <td>
                            <!-- Edit Button -->
                            <a href="edit_supplier.php?id=<?php echo $row['supplier_id']; ?>" class="edit-button">Edit</a>

                            <!-- Delete Button -->
                            <a href="delete_supplier.php?id=<?php echo $row['supplier_id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this supplier?');">Delete</a>
                        </td>
                        <td>
    <a href="rate_supplier.php?id=<?php echo $row['supplier_id']; ?>" class="rate-button">Rate Supplier</a> <!-- Rate Supplier button -->
</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="add_supplier.php" class="add-supplier-btn">Add New Supplier</a>
        <a href="dashboard.php" class="dashboard-button">Dashboard</a> 
    </section>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
