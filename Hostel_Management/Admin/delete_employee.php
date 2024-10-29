<?php
include 'db_connect.php';

$id = $_GET['id'];
$deleteQuery = "DELETE FROM employees WHERE id = $id";

if ($conn->query($deleteQuery) === TRUE) {
    header("Location: view_employee.php");
    exit;
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
