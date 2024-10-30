<?php
include 'db_connect.php'; // Include database connection

// Check if booking ID is provided in the URL
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Prepare and execute the delete query
    $deleteQuery = "DELETE FROM Bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();

    // Redirect back to bookings list after deletion
    if ($stmt->affected_rows > 0) {
        $notification = "Booking deleted successfully!";
    } else {
        $notification = "Error: Unable to delete booking.";
    }

    $stmt->close(); // Close statement
} else {
    $notification = "Invalid request. Booking ID not provided.";
}

// Close database connection
$conn->close(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Booking</title>
    <link rel="stylesheet" href="delete_booking.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .notification {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            color: #fff;
            background-color: #4CAF50; /* Green */
        }
        .button-container {
            text-align: center;
        }
        .back-btn {
            background-color: #2196F3; /* Blue */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .back-btn:hover {
            background-color: #1976D2; /* Darker blue */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Delete Booking</h2>
        
        <?php if (isset($notification)): ?>
            <div class="notification">
                <?php echo $notification; ?>
            </div>
        <?php endif; ?>

        <div class="button-container">
            <a href="bookings.php" class="back-btn">Back to Bookings List</a>
        </div>
    </div>
</body>
</html>
