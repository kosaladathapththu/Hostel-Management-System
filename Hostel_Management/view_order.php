<?php
session_start(); // Start session for authentication check

// Check if the user is logged in as a matron
if (!isset($_SESSION['matron_id'])) {
    header("Location: matron_auth.php"); // Redirect if not logged in
    exit();
}

include 'db_connect.php'; // Include database connection

// Fetch the matron's details
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

// Fetch orders with only the required columns
$ordersQuery = "
SELECT 
    order_id, 
    item_name, 
    quantity, 
    order_date, 
    status AS order_status, 
    bill_amount
FROM Orders
 ORDER BY order_id DESC";
$ordersResult = $conn->query($ordersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <link rel="stylesheet" href="stylesresident.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Modal Styling */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
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
            <h2 style="margin-top:20px;">Orders</h2>
            <div class="breadcrumbs">
                <a href="request_order.php" class="breadcrumb-item">
                    <i class="fas fa-plus"></i> New Order
                </a>
                <span class="breadcrumb-separator">|</span>
                <a href="dashboard.php" class="breadcrumb-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>

            <table>
                <thead>
            
                    <tr>
                        <th style="position: sticky; top: 0; background-color: #08524f; color: white; padding: 10px;">Order ID</th>
                        <th style="position: sticky; top: 0; background-color: #08524f; color: white; padding: 10px;">Item Name</th>
                        <th style="position: sticky; top: 0; background-color: #08524f; color: white; padding: 10px;">Quantity</th>
                        <th style="position: sticky; top: 0; background-color: #08524f; color: white; padding: 10px;">Order Date</th>
                        <th style="position: sticky; top: 0; background-color: #08524f; color: white; padding: 10px;">Status</th>
                        <th style="position: sticky; top: 0; background-color: #08524f; color: white; padding: 10px;">Bill Amount</th>
                        <th style="position: sticky; top: 0; background-color: #08524f; color: white; padding: 10px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $ordersResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['order_status']); ?></td>
                            <td><?php echo htmlspecialchars($row['bill_amount']); ?></td>
                            <td>
                                <?php if ($row['order_status'] == 'Bill Assigned'): ?>
                                    <button class="pay-btn" onclick="openModal(<?php echo $row['order_id']; ?>, '<?php echo $row['bill_amount']; ?>')">Pay</button>
                                <?php else: ?>
                                    <a href="generate_receipt.php?id=<?php echo $row['order_id']; ?>" class="print-link">Print</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>

    <!-- Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Payment Confirmation</h2><br><br>
            <p id="orderDetails"></p>
            <form action="process_order.php" method="post">
                <input type="hidden" id="order_id" name="order_id">
                <button type="submit">Confirm Payment</button>
            </form>
        </div>
    </div>

    <script>
        // Open Modal
        function openModal(orderId, billAmount) {
            document.getElementById('paymentModal').style.display = 'block';
            document.getElementById('orderDetails').innerText = `Order ID: ${orderId} \n\n  Bill Amount: R.S.${billAmount}`;
            document.getElementById('order_id').value = orderId;
        }

        // Close Modal
        function closeModal() {
            document.getElementById('paymentModal').style.display = 'none';
        }
    </script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
