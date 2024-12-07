<?php
include 'db_connect.php';

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
";
$ordersResult = $conn->query($ordersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <link rel="stylesheet" href="view_order.css">
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
    <header>
        <h1>Salvation Army Girls Hostel - Orders List</h1>
        <p>User: [Matron Name] <a href="logout.php">Logout</a></p>
    </header>

    <section>
        <h2>Orders</h2>

        <table>
            <tr>
                <th>Order ID</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Bill Amount</th>
                <th>Actions</th> <!-- Action Column -->
            </tr>
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
                        <button onclick="openModal(<?php echo $row['order_id']; ?>, '<?php echo $row['bill_amount']; ?>')">Pay</button>
                    <?php else: ?>
                        <a href="generate_receipt.php?id=<?php echo $row['order_id']; ?>">Print</a> <!-- Print Action -->
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <div>
            <a href="request_order.php">New Order</a>
            <a href="dashboard.php">Dashboard</a>
        </div>
    </section>

    <!-- Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Payment Confirmation</h2>
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
            document.getElementById('orderDetails').innerText = `Order ID: ${orderId}, Bill Amount: $${billAmount}`;
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
