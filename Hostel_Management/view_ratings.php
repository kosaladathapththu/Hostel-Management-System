<?php
include 'db_connect.php'; // Include database connection

// Fetch all ratings and join with suppliers to display supplier name
$ratingsQuery = "
SELECT r.rating_id, s.supplier_name, r.rating, r.comments, r.rated_at
FROM Ratings r
JOIN Suppliers s ON r.supplier_id = s.supplier_id
ORDER BY r.rated_at DESC";
$ratingsResult = $conn->query($ratingsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Ratings</title>
c
</head>
<body>
    <header>
        <h1>Salvation Army Girls Hostel - View Supplier Ratings</h1>
        <p>User: [Matron Name] <a href="logout.php">Logout</a></p>
    </header>

    <section>
        <h2>All Supplier Ratings</h2>
        <table>
            <thead>
                <tr>
                    <th>Rating ID</th>
                    <th>Supplier Name</th>
                    <th>Rating</th>
                    <th>Comments</th>
                    <th>Rated At</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $ratingsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['rating_id']; ?></td>
                        <td><?php echo $row['supplier_name']; ?></td>
                        <td><?php echo $row['rating']; ?></td>
                        <td><?php echo $row['comments']; ?></td>
                        <td><?php echo $row['rated_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="view_suppliers.php">Back to Supplier List</a>
    </section>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
