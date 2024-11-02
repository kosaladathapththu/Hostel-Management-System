<?php
// Connect to the database
include 'db_connect.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Room Vacancy</title>
    <link rel="stylesheet" href="view_room_vacancy.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<header>
    <h1>Salvation Army Girls Hostel - Guest Dashboard</h1>
    <h2> View room Vacancy</h2>
    <div class="user-info">
        <p>Welcome, </p>
        <br><a href="guest_logout.php">Logout</a>
    </div>
</header>

<body>
    <h2>Available Rooms</h2>

    <?php
    // Query to count available rooms
    $count_query = "SELECT COUNT(*) as available_count FROM rooms WHERE status = 'available'";
    $count_result = mysqli_query($conn, $count_query);
    $count_row = mysqli_fetch_assoc($count_result);
    $available_count = $count_row['available_count'];
    echo "<p>Total Available Rooms: <strong>$available_count</strong></p>";
    ?>

    <table border="1">
        <tr>
            <th>Room Number</th>
            <th>Capacity</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>

        <?php
        // Query to fetch details of available rooms
        $query = "SELECT room_number, capacity, status, created_at FROM rooms WHERE status = 'available'";
        $result = mysqli_query($conn, $query);

        // Loop through the results and display each available room
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['room_number'] . "</td>";
            echo "<td>" . $row['capacity'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
        }
        ?>

    </table>
</body>
</html>
