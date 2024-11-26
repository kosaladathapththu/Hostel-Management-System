<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $applicant_id = $_GET['id'];

    // Step 1: Retrieve applicant data
    $selectQuery = "SELECT * FROM applicants WHERE applicant_id = ?";
    $stmtSelect = $conn->prepare($selectQuery);
    $stmtSelect->bind_param("i", $applicant_id);
    $stmtSelect->execute();
    $result = $stmtSelect->get_result();

    if ($result->num_rows == 1) {
        $applicant = $result->fetch_assoc();

        // Step 2: Insert data into residents table
        $insertQuery = "INSERT INTO residents (resident_name, resident_id, resident_DOB, email, resident_contact, resident_room_no, status, created_at, username, password, profile_picture, resident_form)
                        VALUES (?, ?, ?, ?, ?, ?, 'Active', NOW(), ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($insertQuery);
        $stmtInsert->bind_param(
            "ssisssssss",
            $applicant['name'],
            $applicant['national_id'],
            $applicant['age'],
            $applicant['email'],
            $applicant['phone'],
            $applicant['room_id'],
            $applicant['username'],
            $applicant['password'],
            $applicant['profile_picture'],
            $applicant['resident_form']
        );

        if ($stmtInsert->execute()) {
            // Step 3: Delete the applicant from applicants table
            $deleteQuery = "DELETE FROM applicants WHERE applicant_id = ?";
            $stmtDelete = $conn->prepare($deleteQuery);
            $stmtDelete->bind_param("i", $applicant_id);
            $stmtDelete->execute();

            header("Location: waiting_list.php?success=approved");
            exit();
        } else {
            echo "Error inserting data into residents: " . $stmtInsert->error;
        }
    } else {
        echo "Applicant not found.";
    }
} else {
    echo "Invalid request.";
}
?>
