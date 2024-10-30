<?php
include 'db_connect.php'; // Include database connection

// Check if the supplier ID is set in the URL
if (isset($_GET['id'])) {
    // Fetch the supplier ID from the URL
    $supplierId = intval($_GET['id']); // Use intval() to ensure it's an integer

    // Fetch supplier details
    $supplierQuery = "SELECT supplier_name FROM Suppliers WHERE supplier_id = $supplierId";
    $supplierResult = $conn->query($supplierQuery);

    // Check if the supplier exists
    if ($supplierResult && $supplierResult->num_rows > 0) {
        $supplier = $supplierResult->fetch_assoc();
    } else {
        echo "Supplier not found.";
        exit; // Stop further execution
    }
} else {
    echo "Invalid request. Supplier ID not provided.";
    exit; // Stop further execution
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $rating = $_POST['rating'];
    $comments = $_POST['comments'];

    // Insert rating into the database
    $insertRatingQuery = "INSERT INTO Ratings (supplier_id, rating, comments) VALUES ('$supplierId', '$rating', '$comments')";
    if ($conn->query($insertRatingQuery) === TRUE) {
        echo "Rating submitted successfully.";
    } else {
        echo "Error: " . $insertRatingQuery . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rate Supplier</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>Rate Supplier: <?php echo htmlspecialchars($supplier['supplier_name']); ?></h1>
        <link rel="stylesheet" href="rate_supplier.css"> <!-- Link to your CSS file -->
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    </header>

    <section>
        <h2>Rate Supplier</h2>
        <form method="POST" action="">
            <label for="rating">Rating (1 to 5):</label>
            <input type="number" name="rating" min="1" max="5" required>
            <br>
            <label for="comments">Comments:</label>
            <textarea name="comments"></textarea>
            <br>
            <input type="submit" value="Submit Rating">
        </form>
        <a href="view_suppliers.php">Back to Supplier List</a>
        <div>
        <!-- View Ratings button added here -->
        <a href="view_ratings.php?id=<?php echo $supplierId; ?>" class="view-ratings-button">View Ratings</a> 
        <a href="dashboard.php" class="dashboard-button">Dashboard</a> <!-- Dashboard button -->
        </div>
    </section>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
