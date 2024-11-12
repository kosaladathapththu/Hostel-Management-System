<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to admin login if not logged in
    exit;
}

// Fetch pending matrons
$query = "SELECT * FROM Matron_Vacancys WHERE status = 'pending'";
$result = $conn->query($query);

// Approve matron
if (isset($_POST['approve'])) {
    $vacancyId = $_POST['vacancy_id'];

    // Move matron to Matron table
    $selectQuery = "SELECT * FROM Matron_Vacancys WHERE vacancy_id = ?";
    $stmt = $conn->prepare($selectQuery);
    $stmt->bind_param("i", $vacancyId);
    $stmt->execute();
    $vacancyResult = $stmt->get_result();

    if ($vacancyResult->num_rows > 0) {
        $matron = $vacancyResult->fetch_assoc();

        $insertQuery = "INSERT INTO Matrons (first_name, second_name, email, birth_date, city, password) VALUES (?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ssssss", $matron['first_name'], $matron['second_name'], $matron['email'], $matron['birth_date'], $matron['city'], $matron['password']);
        
        if ($insertStmt->execute()) {
            // Update status in Matron_Vacancy
            $updateQuery = "UPDATE Matron_Vacancys SET status = 'approved' WHERE vacancy_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("i", $vacancyId);
            $updateStmt->execute();

            echo "Matron approved successfully!";
        } else {
            echo "Error approving matron: " . $insertStmt->error;
        }

        $insertStmt->close();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval</title>
</head>
<body>
    <h2>Pending Matron Approvals</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Second Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['vacancy_id']; ?></td>
            <td><?php echo $row['first_name']; ?></td>
            <td><?php echo $row['second_name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td>
                <form action="admin_approve_matron.php" method="POST">
                    <input type="hidden" name="vacancy_id" value="<?php echo $row['vacancy_id']; ?>">
                    <button type="submit" name="approve">Approve</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
