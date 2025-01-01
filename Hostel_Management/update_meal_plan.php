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

// Check if the form is submitted
if (isset($_POST['update'])) {
    $meal_id = $_POST['meal_id'];
    $meal_name = $_POST['meal_name'];
    $description = $_POST['description'];
    $date = $_POST['date'];

    // Update the meal plan in the database
    $sql = "UPDATE meal_plans SET meal_name = ?, description = ?, date = ? WHERE meal_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $meal_name, $description, $date, $meal_id);

    if ($stmt->execute()) {
        echo "Meal plan updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch the meal plan details to update
if (isset($_GET['meal_id'])) {
    $meal_id = $_GET['meal_id'];

    $sql = "SELECT * FROM meal_plans WHERE meal_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $meal_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $meal = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Meal Plan</title>
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

    <!-- Main Content -->
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
            <h2 style="margin-top:20px;">Update Meal Plan</h2>
            <div class="breadcrumbs">
                <a href="view_meal_plans.php" class="breadcrumb-item">
                    <i class="fas fa-utensils"></i> View Meal Plans
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="view_feedback.php" class="breadcrumb-item">
                    <i class="fas fa-star"></i> View Feedbacks
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="dashboard.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>

            <?php if (isset($meal)) { ?>
                <form method="POST" action="update_meal_plan.php">
                    <input type="hidden" name="meal_id" value="<?php echo $meal['meal_id']; ?>">

                    <label>Meal Name:</label>
                    <input type="text" name="meal_name" value="<?php echo $meal['meal_name']; ?>" required><br>

                    <label>Description:</label>
                    <textarea id="description" name="description"><?php echo $meal['description']; ?></textarea><br>

                    <label>Date:</label>
                    <input type="date" name="date" value="<?php echo $meal['date']; ?>" required><br>

                    <button type="submit" name="update">Update Meal Plan</button>

                </form>
            <?php } else { ?>
                <p>Meal plan not found!</p>
            <?php } ?>

        </section>
    </div>

</body>
</html>

<?php
$conn->close();
?>
