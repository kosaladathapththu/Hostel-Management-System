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

// Updated query to reflect the renamed columns
$residentsQuery = "
    SELECT 
        r.id, 
        r.resident_name AS name, 
        r.resident_id AS national_id, 
        r.resident_DOB AS age, 
        r.email, 
        r.resident_contact AS phone, 
        rm.room_number, 
        r.status, 
        r.created_at 
    FROM residents r 
    LEFT JOIN Rooms rm 
    ON r.resident_room_no = rm.room_id"; // Updated join condition
$residentsResult = $conn->query($residentsQuery);
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Residents</title>

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
            <h2 style="margin-top:20px;">Residents List</h2>
            <div class="breadcrumbs">
                <a href="add_resident.php" class="breadcrumb-item">
                    <i class="fas fa-plus"></i> Add new Resident
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="dashboard.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="waiting_list.php" class="breadcrumb-item">
                    <i class="fas fa-list"></i> Waiting List
                </a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>National ID</th>
                        <th>Date of Birth</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Room Number</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $residentsResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo isset($row['national_id']) ? $row['national_id'] : 'N/A'; ?></td>
                            <td><?php echo isset($row['age']) ? $row['age'] : 'N/A'; ?></td>
                            <td><?php echo isset($row['email']) ? $row['email'] : 'N/A'; ?></td>
                            <td><?php echo isset($row['phone']) ? $row['phone'] : 'N/A'; ?></td>
                            <td><?php echo isset($row['room_number']) ? $row['room_number'] : 'N/A'; ?></td>
                            <td><?php echo $row['status'] === 'active' ? 'Active' : 'Inactive'; ?></td>
                            <td><?php echo isset($row['created_at']) ? $row['created_at'] : 'N/A'; ?></td>
                            <td>
                                <a href="edit_resident.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a><br><br>
                                <a href="delete_resident.php?id=<?php echo $row['id']; ?>" class="delete-btn">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>

<?php
$conn->close(); 
?>
