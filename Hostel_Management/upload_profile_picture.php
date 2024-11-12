<?php
include 'db_connect.php'; 
session_start();

if (!isset($_SESSION['resident_id'])) {
    header("Location: login.php");
    exit();
}

$resident_id = $_SESSION['resident_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $targetDir = "uploads/profile_pictures/";
    $fileName = basename($_FILES["profile_picture"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath)) {
        $updateQuery = "UPDATE residents SET profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $targetFilePath, $resident_id);
        
        if ($stmt->execute()) {
            $_SESSION['profile_picture'] = $targetFilePath; 
            header("Location: resident_dashboard.php");
            exit();
        } else {
            echo "Database update failed.";
        }
    } else {
        echo "File upload failed.";
    }
} else {
    echo "Invalid request.";
}
?>
