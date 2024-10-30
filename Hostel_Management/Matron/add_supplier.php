<?php
session_start(); // Start the session
include 'db_connect.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $supplierName = $_POST['supplier_name'];
    $category = $_POST['category'];
    $contact = $_POST['contact'];
    $createdAt = date('Y-m-d H:i:s'); // Current date and time

    // Generate a unique username based on supplier name
    $username = strtolower(str_replace(' ', '_', $supplierName)) . "_" . uniqid();

    // Insert supplier into the database, including the generated username
    $sql = "INSERT INTO Suppliers (supplier_name, category, contact, created_at, username) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $supplierName, $category, $contact, $createdAt, $username);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Supplier added successfully!";
        header("Location: view_suppliers.php"); // Redirect to the suppliers list
        exit();
    } else {
        $_SESSION['error'] = "Error adding supplier: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Supplier</title>
    <link rel="stylesheet" href="addsupplier.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Add Supplier</h1>
    </header>
    <section>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="notification success">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="notification error">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="supplier_name">Supplier Name:</label>
                <input type="text" id="supplier_name" name="supplier_name" required>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" id="category" name="category" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact:</label>
                <input type="text" id="contact" name="contact" required>
            </div>
            <button type="submit" class="add-button">Add Supplier</button>
        </form>

        <div class="button-group">
            <a href="view_suppliers.php" class="view-button">View Suppliers</a>
            <a href="dashboard.php" class="dashboard-button">Dashboard</a>
        </div>
    </section>
</body>
</html>

<?php $conn->close(); ?>
