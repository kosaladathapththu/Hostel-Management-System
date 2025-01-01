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

// Fetch meal plans from the database
$sql = "SELECT meal_id, meal_name, description, date, created_by, created_at FROM meal_plans ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Meal Plans</title>
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
        <h2 style="margin-top:20px;">Meal Plans List</h2>
            <div class="breadcrumbs" >
                <a href="add_meal_plan.php" class="breadcrumb-item">
                    <i class="fas fa-plus"></i> Add new Meal Plan
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

            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Meal ID</th>
                            <th>Meal Name</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Action</th> <!-- Action column for Edit/Delete -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['meal_id']; ?></td>
                                <td><?php echo $row['meal_name']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['created_by']; ?></td>
                                <td><?php echo $row['created_at']; ?></td>
                                <td>
                                    <a href="update_meal_plan.php?meal_id=<?php echo $row['meal_id']; ?>" class="edit-btn">Edit</a>
                                    <a href="delete_meal_plan.php?meal_id=<?php echo $row['meal_id']; ?>" class="delete-btn">Delete</a>
                                </td>
                            </tr><br><br>
                        <?php endwhile; ?>
                    </tbody>
                </table><br><br><br>
            <?php else: ?>
                <p>No meal plans available.</p><br><br>
            <?php endif; ?>

<form>
            <!-- Report Generation Buttons -->
            <center><div class="button-container">
                <h3>Generate Reports</h3><br>
                <a href="monthly_report.php" class="button">Monthly Report</a><br><br>
                <a href="annual_report.php" class="button">Annual Report</a>
            </div></center>
            </form>
        </section>
    </div>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
