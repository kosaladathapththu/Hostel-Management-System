<?php
// Include database connection
include 'db_connect.php';

// Fetch employee data
$query = "SELECT employee_id, name, emp_gender, position, email, phone, CONCAT(emp_addr_street, ', ', emp_addr_city, ', ', emp_addr_province) AS full_address, status, created_at, national_id, leave_balance 
          FROM employees";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        table th {
            background-color: #4CAF50;
            color: white;
        }
        .status-active {
            color: green;
            font-weight: bold;
        }
        .status-inactive {
            color: red;
            font-weight: bold;
        }
        .print-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .print-btn:hover {
            background-color: #45a049;
        }

        @media print {
            .print-btn {
                display: none; /* Hide the print button */
            }
            table th:nth-child(1), /* Hide the ID column header */
        table td:nth-child(1){} /* Hide the ID column data */
                table th:nth-child(3), /* Hide the ID column header */
            table td:nth-child(3) { /* Hide the ID column data */
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="header2">
            <img src="images/header.png" alt="Header Image">
        </div>
        <h1>Employee Report</h1>
        <button class="print-btn" onclick="printReport()">Print Employee Report</button>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Position</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Join Date</th>
                    <th>National ID</th>
                    <th>Leave Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['employee_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['emp_gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['position']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['full_address']); ?></td>
                            <td>
                                <?php echo $row['status'] == 1 
                                    ? "<span class='status-active'>Active</span>" 
                                    : "<span class='status-inactive'>Inactive</span>"; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td><?php echo htmlspecialchars($row['national_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['leave_balance']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11">No records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Footer Section -->
        <div class="footer">
            <img src="images/footer.png" alt="Footer Image">
        </div>
    </div>
    <script>
        function printReport() {
            // Trigger the print dialog
            window.print();
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
