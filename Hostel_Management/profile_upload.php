<?php
include 'db_connect.php'; // Include database connection
session_start(); // Start session

// Check if a file was uploaded
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    // Get file information
    $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
    $fileName = $_FILES['profile_picture']['name'];
    $fileSize = $_FILES['profile_picture']['size'];
    $fileType = $_FILES['profile_picture']['type'];
    
    // Specify the path where the file will be uploaded
    $uploadFileDir = 'uploads/';
    $dest_path = $uploadFileDir . basename($fileName);
    
    // Check if uploads directory exists
    if (!is_dir($uploadFileDir)) {
        mkdir($uploadFileDir, 0755, true); // Create directory if it doesn't exist
    }

    // Move the file to the uploads directory
    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        // File upload successful, update the resident's profile picture in the database
        $resident_id = $_SESSION['resident_id'];
        $updateQuery = "UPDATE residents SET profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $dest_path, $resident_id);
        $stmt->execute();
        
        // Redirect or display success message
        header("Location: resident_dashboard.php"); // Redirect back to the dashboard
        exit();
    } else {
        echo "Failed to upload image.";
    }
}
?>
