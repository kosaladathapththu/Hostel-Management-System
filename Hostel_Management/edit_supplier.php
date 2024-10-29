<?php
session_start(); // Start session
include 'db_connect.php'; // Include database connection

// Fetch the supplier ID from the URL
$supplierId = $_GET['id'];

// Fetch the supplier details
$supplierQuery = "SELECT * FROM Suppliers WHERE supplier_id = $supplierId";
$supplierResult = $conn->query($supplierQuery);
$supplier = $supplierResult->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated form data
    $supplierName = $_POST['supplier_name'];
    $category = $_POST['category'];
    $contact = $_POST['contact'];

    // Update the supplier in the database
    $updateQuery = "UPDATE Suppliers SET supplier_name = '$supplierName', category = '$category', contact = '$contact' WHERE supplier_id = $supplierId";
    if ($conn->query($updateQuery) === TRUE) {
        $_SESSION['message'] = "Supplier updated successfully!";
        header("Location: edit_supplier.php?id=$supplierId"); // Redirect to the same page to show the message
        exit();
    } else {
        echo "Error: " . $updateQuery . "<br>" . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Supplier</title>
    <link rel="stylesheet" href="edit_supplier.css"> <!-- Link to your CSS file -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - Edit Supplier</h1>
        <p>User: [Matron Name] <a href="logout.php">Logout</a></p>
    </header>

    <section class="edit-section">
        <h2>Edit Supplier</h2>
        
        <!-- Notification Section -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="notification success">
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']); // Clear the message after displaying
                ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="supplier_name">Supplier Name:</label>
                <input type="text" name="supplier_name" value="<?php echo $supplier['supplier_name']; ?>" required>
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <select name="category" required>
                    <option value="food" <?php if($supplier['category'] == 'food') echo 'selected'; ?>>Food</option>
                    <option value="furniture" <?php if($supplier['category'] == 'furniture') echo 'selected'; ?>>Furniture</option>
                    <option value="utilities" <?php if($supplier['category'] == 'utilities') echo 'selected'; ?>>Utilities</option>
                    <option value="repair_services" <?php if($supplier['category'] == 'repair_services') echo 'selected'; ?>>Repair Services</option>
                </select>
            </div>

            <div class="form-group">
                <label for="contact">Contact:</label>
                <input type="text" name="contact" value="<?php echo $supplier['contact']; ?>" required>
            </div>

            <button type="submit" class="update-button">Update Supplier</button>
           
        </form>
        <a href="view_suppliers.php" class="back-button">Back to Supplier List</a>
        <a href="dashboard.php" class="dashboard-button">Dashboard</a> 
    </section>

    <!-- JavaScript to hide the notification after 5 seconds -->
    <script>
        window.onload = function() {
            const notification = document.querySelector('.notification.success');
            if (notification) {
                setTimeout(() => {
                    notification.style.display = 'none'; // Hide the notification
                }, 5000); // 5000ms = 5 seconds
            }
        };
    </script>
</body>
</html>


<?php
$conn->close(); // Close the database connection
?>
