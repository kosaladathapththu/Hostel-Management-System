<?php
session_start(); // Start session for authentication check

// Check if the user is logged in as a matron
if (!isset($_SESSION['matron_id'])) {
    header("Location: matron_auth.php"); // Redirect if not logged in
    exit();
}

include 'db_connect.php'; // Include database connection

// Fetch the matron's details (Extra safety check)
$matron_id = $_SESSION['matron_id'];
$matronQuery = "SELECT first_name FROM Matrons WHERE matron_id = ?";
$stmt = $conn->prepare($matronQuery);
$stmt->bind_param("i", $matron_id);
$stmt->execute();
$matronResult = $stmt->get_result();

if ($matronResult->num_rows === 0) {
    header("Location: matron_auth.php"); // Redirect if matron not found
    exit();
}

// Assign matron's first name
$matronData = $matronResult->fetch_assoc();
$matron_first_name = $matronData['first_name'];

// Check if the supplier ID is set in the URL
if (isset($_GET['id'])) {
    // Fetch the supplier ID from the URL
    $supplierId = intval($_GET['id']); // Use intval() to ensure it's an integer

    // Fetch supplier details
    $supplierQuery = "SELECT supplier_name FROM Suppliers WHERE supplier_id = ?";
    $stmt = $conn->prepare($supplierQuery);
    $stmt->bind_param("i", $supplierId);
    $stmt->execute();
    $supplierResult = $stmt->get_result();

    // Check if the supplier exists
    if ($supplierResult && $supplierResult->num_rows > 0) {
        $supplier = $supplierResult->fetch_assoc();
    } else {
        echo "Supplier not found.";
        exit(); // Stop further execution
    }
} else {
    echo "Invalid request. Supplier ID not provided.";
    exit(); // Stop further execution
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $rating = intval($_POST['rating']);
    $comments = $_POST['comments'];

    // Validate the input data
    if ($rating < 1 || $rating > 5) {
        echo "Error: Rating must be between 1 and 5.";
        exit();
    }

    // Insert rating into the database with matron_id
    $insertRatingQuery = "INSERT INTO Ratings (supplier_id, matron_id, rating, comments) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertRatingQuery);
    $stmt->bind_param("iiis", $supplierId, $matron_id, $rating, $comments);

    if ($stmt->execute()) {
        echo "Rating submitted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rate Supplier</title>
    <link rel="stylesheet" href="stylesresident.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Matron Panel</h2>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="residents.php"><i class="fas fa-users"></i> Residents</a></li>
            <li><a href="bookings.php"><i class="fas fa-calendar-check"></i> Check-ins/Outs</a></li>
            <li><a href="rooml.php"><i class="fas fa-door-open"></i> Rooms</a></li>
            <li><a href="payments.php"><i class="fas fa-money-check-alt"></i> Payments</a></li>
            <li><a href="view_suppliers.php"><i class="fas fa-truck"></i> Suppliers</a></li>
            <li><a href="view_order.php"><i class="fas fa-receipt"></i> Orders</a></li>
            <li><a href="view_inventory.php"><i class="fas fa-boxes"></i> Inventory</a></li>
            <li><a href="view_calendar.php"><i class="fas fa-calendar"></i> Events</a></li>
            <li><a href="view_meal_plans.php"><i class="fas fa-utensils"></i> Meal Plans</a></li>
        </ul>
        <button onclick="window.location.href='matron_logout.php'" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>

    <div class="main-content">
        <header>
            <div class="header-left">
                <img src="The_Salvation_Army.png" alt="Logo" class="logo"> 
            </div>
            <center><b><h1>Salvation Army Girls Hostel</h1></b></center>
            <div class="header-right">
                <p>Welcome, <?php echo htmlspecialchars($matron_first_name); ?></p>
            </div>
        </header>

        <section>
            <h2 style="margin-top:20px;">Rate Supplier: <?php echo htmlspecialchars($supplier['supplier_name']); ?></h2>
            <div class="breadcrumbs">
                <a href="view_suppliers.php" class="breadcrumb-item">
                    <i class="fas fa-truck"></i> View Suppliers
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="view_ratings.php" class="breadcrumb-item">
                    <i class="fas fa-star"></i> View Ratings
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="dashboard.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>


            <form method="POST" action="">
                <label for="rating">Rating (1 to 5):</label>
                <input type="number" name="rating" min="1" max="5" required>
                <br>
                <label for="comments">Comments:</label>
                <textarea name="comments"></textarea>
                <br><br>
                <input type="submit" value="Submit Rating">
            </form>
            <center>
                <img src="review.png" alt="review image" style="width: 400px; height: auto; box-shadow: 10px 10px 8px 10px rgba(0, 0, 0, 0.2); margin-top: 20px; margin-left: 400px; border-radius: 1000px;">
            </center>
        </section>
    </div>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
