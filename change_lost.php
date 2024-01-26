<?php
session_start();

require 'dbConn.php';

// Fetch user details from the User table
$sqlUser = "SELECT * FROM User";
$stmtUser = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmtUser, $sqlUser)) {
    mysqli_stmt_execute($stmtUser);
    $resultUser = mysqli_stmt_get_result($stmtUser);

    if ($resultUser) {
        $user_details = mysqli_fetch_assoc($resultUser);
    } else {
        die("Fetch user query failed: " . mysqli_error($conn));
    }
} else {
    die("User query preparation failed: " . mysqli_error($conn));
}

// Assuming you have a LostPet entity with the given fields
$fetch_lost_pet_query = "SELECT LostPet.*, User.User_Name
                    FROM LostPet 
                    JOIN User ON LostPet.User_ID = User.User_ID
                    WHERE LostPet.Lost_ID = ?";
                    
$lostPetID = mysqli_real_escape_string($conn, $_POST['lost_pet_id']);
$stmtFetchLostPet = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmtFetchLostPet, $fetch_lost_pet_query)) {
    mysqli_stmt_bind_param($stmtFetchLostPet, "i", $lostPetID);
    mysqli_stmt_execute($stmtFetchLostPet);
    $resultLostPet = mysqli_stmt_get_result($stmtFetchLostPet);

    if ($resultLostPet) {
        $lost_pet_details = mysqli_fetch_assoc($resultLostPet);

        // Your code to display or process $lost_pet_details goes here
        // You can access the user name using $lost_pet_details['User_Name']
    } else {
        die("Lost pet not found");
    }
} else {
    die("Fetch lost pet query preparation failed: " . mysqli_error($conn));
}

// Ensure the user is logged in; adjust this according to your authentication mechanism
if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lostPetID = mysqli_real_escape_string($conn, $_POST['lost_pet_id']);
    $lostPetName = mysqli_real_escape_string($conn, $_POST['lost_pet_name']);
    $lostPetBreed = mysqli_real_escape_string($conn, $_POST['lost_pet_breed']);
    $lostPetAge = mysqli_real_escape_string($conn, $_POST['lost_pet_age']);
    $lostPetBody = mysqli_real_escape_string($conn, $_POST['lost_pet_body']);
    $lostPetGender = mysqli_real_escape_string($conn, $_POST['lost_pet_gender']);
    $lostPetDate = mysqli_real_escape_string($conn, $_POST['lost_pet_date']);
    $lostPetLocation = mysqli_real_escape_string($conn, $_POST['lost_pet_location']);
    
    // Check if a new photo was uploaded
    if (isset($_FILES['lost_pet_photo']) && $_FILES['lost_pet_photo']['error'] == 0) {
        $photoTemp = $_FILES['lost_pet_photo']['tmp_name'];
        $photoName = $_FILES['lost_pet_photo']['name'];
        $photoPath = "" . $photoName;

        // Move the uploaded file to the desired location
        if (move_uploaded_file($photoTemp, $photoPath)) {
            // Update lost pet data with the new photo path
            $updateLostPetQuery = "UPDATE LostPet SET Lost_Name = ?, Lost_Breed = ?, Lost_Age = ?, Lost_Body = ?, Lost_Gender = ?, Lost_Date = ?, Lost_Location = ?, Lost_Photo = ? WHERE Lost_ID = ?";
            $stmt = mysqli_prepare($conn, $updateLostPetQuery);
            mysqli_stmt_bind_param($stmt, "ssssssssi", $lostPetName, $lostPetBreed, $lostPetAge, $lostPetBody, $lostPetGender, $lostPetDate, $lostPetLocation, $photoPath, $lostPetID);
        } else {
            // Handle file upload error
            echo "File upload failed.";
            exit();
        }
    } else {
        // If no new photo is uploaded, update lost pet data without changing the existing photo
        $updateLostPetQuery = "UPDATE LostPet SET Lost_Name = ?, Lost_Breed = ?, Lost_Age = ?, Lost_Body = ?, Lost_Gender = ?, Lost_Date = ?, Lost_Location = ? WHERE Lost_ID = ?";
        $stmt = mysqli_prepare($conn, $updateLostPetQuery);
        mysqli_stmt_bind_param($stmt, "sssssssi", $lostPetName, $lostPetBreed, $lostPetAge, $lostPetBody, $lostPetGender, $lostPetDate, $lostPetLocation, $lostPetID);
    }

    if (mysqli_stmt_execute($stmt)) {
        // Check if any rows were affected during the update
        $affectedRows = mysqli_stmt_affected_rows($stmt);

        if ($affectedRows > 0) {
            // Redirect to the edit_information.php page with a success query parameter
            header("Location: edit_information.php?lost_id=" . $lostPetID . "&update=success");
            exit();
        } else {
            // Log the values being updated for debugging
            error_log("No changes were made. Lost Pet ID: $lostPetID, Data: " . print_r($_POST, true));

            // Handle the case where no rows were affected (no actual update happened)
            $error_message = "No changes were made. Lost Pet ID may not exist or data is unchanged.";
            header("Location: edit_information.php?lost_id=" . $lostPetID . "error=nochanges&message=" . urlencode($error_message));
            exit();
        }
    } else {
        // Handle the error by logging and redirecting with an error parameter
        $error_message = "Lost Pet update failed: " . mysqli_error($conn);
        error_log($error_message);
        header("Location: edit_information.php?lost_id=" . $lostPetID . "error=lostpetupdatefailed&message=" . urlencode($error_message));
        exit();
    }
}

// If the script reaches this point without handling the form submission, redirect to the edit_information.php page
header("Location: edit_information.php?lost_id=" . $lostPetID . "");
exit();
?>
