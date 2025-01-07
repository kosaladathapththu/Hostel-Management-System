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

// Process form data when submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $meal_name = $_POST['meal_name'];
    $description = $_POST['description'];
    $date = $_POST['date'];

    // Prepare the SQL query to insert the meal plan into the database
    $insertQuery = "INSERT INTO meal_plans (meal_name, description, date, created_by, created_at) 
                    VALUES (?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssss", $meal_name, $description, $date, $matron_first_name);

    if ($stmt->execute()) {
        // If insertion is successful, redirect to the meal plans view page
        header("Location: view_meal_plans.php");
        exit();
    } else {
        // Display error if insertion fails
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Meal Plan</title>
    <link rel="stylesheet" href="stylesresident.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    /* General Styles for the Container */
    .container {
        width: 80%;
        max-width: 600px;
        margin: 10px auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Form Heading */
    .container h2 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
    }

    /* Label Styles */
    label {
        display: block;
        margin-bottom: 8px;
        font-size: 16px;
        color: #333;
        text-align:left;
    }

    /* Input and Textarea Styles */
    input[type="text"],
    textarea,
    input[type="date"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
        background-color: #fff;
    }

    /* Input Focus */
    input[type="text"]:focus,
    textarea:focus,
    input[type="date"]:focus {
        border-color: #007bff;
        outline: none;
    }

    /* Button Styles */
    .submit-btn {
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        color: white;
        font-size: 18px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    /* Button Hover */
    .submit-btn:hover {
        background-color: #0056b3;
    }

    /* Centering the Form */
    .container form {
        display: flex;
        flex-direction: column;
    }

    /* Responsive Styling for Small Screens */
    @media (max-width: 600px) {
        .container {
            width: 90%;
        }
    }
</style>
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
        <h2 style="margin-top:20px;">Add New Meal Plan</h2>
            <!-- Breadcrumbs -->
            <div class="breadcrumbs" style="margin-top: 20px;">
                <a href="view_meal_plans.php" class="breadcrumb-item">
                    <i class="fas fa-utensils"></i> View Meal Plans
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="view_feedback.php" class="breadcrumb-item">
                    <i class="fas fa-star"></i> View Feedbacks
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="dashboard.php" class="breadcrumb-item"><i class="fas fa-home"></i> Dashboard</a>
            </div>

            <!-- Add Meal Plan Form -->
            <center><div class="container">
                <h2>Add Meal Plan</h2>
                <form action="" method="POST">
                    <label for="meal_name">Meal Name:</label>
                    <input type="text" id="meal_name" name="meal_name" placeholder="Enter Meal Name" required>

                    <label for="description">Description:</label>
                    <textarea id="description" name="description" placeholder="Enter Description" required></textarea>

                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required>

                    <button type="submit" class="submit-btn">Add Meal Plan</button>
                </form>
            </div></center>
        </section>
    </div>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
