<?php
include 'db_connect.php';
session_start();

// Ensure the suplier is logged in
if (!isset($_SESSION['supplier_id'])) {
    header("Location: supplier_login.php");
    exit();
}

$matron_id = $_SESSION['suplier_id'];

// Fetch orders that have a status of 'requested' and haven't been accepted yet
$sql = "SELECT o.*, os.supplier_acceptance
        FROM orders o
        LEFT JOIN orderstatus os ON o.order_id = os.order_id
        WHERE o.status = 'requested' AND os.supplier_acceptance IS NULL";
$stmt = $conn->prepare($sql);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Requested Orders</title>
    <link rel="stylesheet" href="style.css">  <!-- Link to your styles -->
</head>
<body>
    <h1>Requested Orders</h1>
    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $orders->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td>
                <?php if ($row['status'] == 'requested' && is_null($row['supplier_acceptance'])) { ?>
                    <form method="POST" action="accept_order.php">
                        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                        <button type="submit" name="accept_order">Accept Order</button>
                    </form>
                <?php } else { ?>
                    <span>Order already accepted or declined</span>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
