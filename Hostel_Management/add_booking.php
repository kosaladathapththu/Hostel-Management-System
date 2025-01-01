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

// Fetch residents to populate dropdown
$residentsQuery = "SELECT id, resident_name FROM Residents";
$residentsResult = $conn->query($residentsQuery);

$notification = ''; // Variable to store notification

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $residentId = $_POST['resident_id'];
    $checkInDate = $_POST['check_in_date'];
    $checkOutDate = $_POST['check_out_date'];

    // Insert data into the resident_checking-checkouts table
    $insertQuery = "INSERT INTO `resident_checking-checkouts` (resident_id, check_in_date, check_out_date) 
                    VALUES ('$residentId', '$checkInDate', '$checkOutDate')";

    if ($conn->query($insertQuery) === TRUE) {
        $notification = "<div class='notification success'>Check-in/out record added successfully.</div>";
    } else {
        $notification = "<div class='notification error'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Check-in/Check-out</title>
    <link rel="stylesheet" href="stylesresident.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        // JavaScript to remove the notification after 5 seconds
        window.onload = function() {
            setTimeout(function() {
                var notification = document.querySelector('.notification');
                if (notification) {
                    notification.style.display = 'none';
                }
            }, 5000); // 5000ms = 5 seconds
        };
    </script>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><i class="fas fa-user-shield"></i> Matron Panel</h2>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="residents.php"><i class="fas fa-users"></i> Residents</a></li>
            <li><a href="bookings.php"><i class="fas fa-calendar-check"></i> Check-ins/Outs</a></li>
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

        <?php echo $notification; ?> <!-- Display the notification here -->

        <section>
        <h2 style="margin-top:20px;">Add Check-in/Check-out for Residents</h2>
            <div class="breadcrumbs">
                <a href="bookings.php" class="breadcrumb-item">
                    <i class="fas fa-arrow-left"></i> Back to Check-ins/Outs List
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="dashboard.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>
            <form method="POST" action="">
                <label for="resident_id">Resident:</label>
                <select name="resident_id" required>
                    <?php while($row = $residentsResult->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['resident_name']; ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="check_in_date">Check-in Date:</label>
                <input type="date" name="check_in_date" required>

                <label for="check_out_date">Check-out Date:</label>
                <input type="date" name="check_out_date" required>

                <input type="submit" value="Add Record">
            </form>
        </section>
    </div>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
