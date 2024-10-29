<?php
include 'db_connect.php';

if (isset($_POST['employee_id'])) {
    $employee_id = $_POST['employee_id'];
    $query = "SELECT position FROM employees WHERE employee_id = $employee_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['position'];  // Output the position for AJAX to handle
    } else {
        echo '';  // No position found
    }
}

$conn->close();
?>
