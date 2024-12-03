<?php
session_start();
include 'db_connect.php'; // Database connection file

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_date = $_POST['service_date'];
    $service_province = $_POST['service_province'];
    $service_city = $_POST['service_city'];
    $service_street = $_POST['service_street'];
    $service_description = $_POST['service_description'];
    $admin_id = $_SESSION['admin_id'];

    $query = "INSERT INTO social_service(admin_id, service_date, service_province, service_city, service_street, service_description) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssss", $admin_id, $service_date, $service_province, $service_city, $service_street, $service_description);

    if ($stmt->execute()) {
        $success_message = "Service added successfully!";
    } else {
        $error_message = "Failed to add service. Please try again.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Social Services</title>
    <link rel="stylesheet" href="add_social_services.css">
</head>
<body>
    <div class="main-content">
        <header>
            <h1>Add Social Service</h1>
        </header>
        <div class="form-container">
            <?php if (isset($success_message)) echo "<p class='success'>$success_message</p>"; ?>
            <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>
            <form method="POST" action="">
                <label for="service_date">Date:</label>
                <input type="date" id="service_date" name="service_date" required>

                <label for="service_province">Province:</label>
    <select id="service_province" name="service_province" onchange="updateDistricts()" required>
    <option value="">--Select Province--</option>
    <option value="Central">Central</option>
    <option value="Eastern">Eastern</option>
    <option value="Northern">Northern</option>
    <option value="Southern">Southern</option>
    <option value="Western">Western</option>
    <option value="North Central">North Central</option>
    <option value="North Western">North Western</option>
    <option value="Uva">Uva</option>
    <option value="Sabaragamuwa">Sabaragamuwa</option>
</select>

<label for="service_city">District:</label>
<select id="service_city" name="service_city" required>
    <option value="">--Select District--</option>
</select>

<script>
    // Object mapping provinces to their districts
    const districtsByProvince = {
        "Central": ["Kandy", "Matale", "Nuwara Eliya"],
        "Eastern": ["Ampara", "Batticaloa", "Trincomalee"],
        "Northern": ["Jaffna", "Kilinochchi", "Mannar", "Vavuniya", "Mullaitivu"],
        "Southern": ["Galle", "Matara", "Hambantota"],
        "Western": ["Colombo", "Gampaha", "Kalutara"],
        "North Central": ["Anuradhapura", "Polonnaruwa"],
        "North Western": ["Kurunegala", "Puttalam"],
        "Uva": ["Badulla", "Moneragala"],
        "Sabaragamuwa": ["Ratnapura", "Kegalle"]
    };

    // Function to update districts based on selected province
    function updateDistricts() {
        const provinceSelect = document.getElementById("service_province");
        const districtSelect = document.getElementById("service_city");
        const selectedProvince = provinceSelect.value;

        // Clear the current district options
        districtSelect.innerHTML = '<option value="">--Select District--</option>';

        // Populate districts if a province is selected
        if (selectedProvince && districtsByProvince[selectedProvince]) {
            districtsByProvince[selectedProvince].forEach(district => {
                const option = document.createElement("option");
                option.value = district;
                option.textContent = district;
                districtSelect.appendChild(option);
            });
        }
    }
</script>
 
                
                <label for="service_street">Street:</label>
                <input type="text" id="service_street" name="service_street" required>

                <label for="service_description">Description:</label>
                <textarea id="service_description" name="service_description" required></textarea>

                <button type="submit">Add Service</button>
            </form>
        </div>
    </div>
</body>
</html>