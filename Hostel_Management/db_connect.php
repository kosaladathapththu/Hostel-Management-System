<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password (blank)
$dbname = "hostel_management"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


