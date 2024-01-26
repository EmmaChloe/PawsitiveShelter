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

// Assuming you have a Pet entity with the given fields
$fetch_pet_query = "SELECT Pet.*, User.User_Name
                    FROM Pet 
                    JOIN User ON Pet.User_ID = User.User_ID
                    WHERE Pet.Pet_ID = ?";
                    
$petID = mysqli_real_escape_string($conn, $_POST['pet_id']);
$stmtFetchPet = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmtFetchPet, $fetch_pet_query)) {
    mysqli_stmt_bind_param($stmtFetchPet, "i", $petID);
    mysqli_stmt_execute($stmtFetchPet);
    $resultPet = mysqli_stmt_get_result($stmtFetchPet);

    if ($resultPet) {
        $pet_details = mysqli_fetch_assoc($resultPet);

        // Your code to display or process $pet_details goes here
        // You can access the user name using $pet_details['User_Name']
    } else {
        die("Pet not found");
    }
} else {
    die("Fetch pet query preparation failed: " . mysqli_error($conn));
}

// Ensure the user is logged in; adjust this according to your authentication mechanism
if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $petID = mysqli_real_escape_string($conn, $_POST['pet_id']);
    $petName = mysqli_real_escape_string($conn, $_POST['pet_name']);
    $petBreed = mysqli_real_escape_string($conn, $_POST['pet_breed']);
    $petAge = mysqli_real_escape_string($conn, $_POST['pet_age']);
    $petBody = mysqli_real_escape_string($conn, $_POST['pet_body']);
    $petGender = mysqli_real_escape_string($conn, $_POST['pet_gender']);
    $petCondition = mysqli_real_escape_string($conn, $_POST['pet_condition']);
    $petVaccinated = mysqli_real_escape_string($conn, $_POST['pet_vaccinated']);
    $petDewormed = mysqli_real_escape_string($conn, $_POST['pet_dewormed']);
    $petNeutered = mysqli_real_escape_string($conn, $_POST['pet_neutered']);
    $petDate = mysqli_real_escape_string($conn, $_POST['pet_date']);
    $petLocation = mysqli_real_escape_string($conn, $_POST['pet_location']);
    $petDescription = mysqli_real_escape_string($conn, $_POST['pet_description']);
    
    // Check if a new photo was uploaded
    if (isset($_FILES['pet_photo']) && $_FILES['pet_photo']['error'] == 0) {
        $photoTemp = $_FILES['pet_photo']['tmp_name'];
        $photoName = $_FILES['pet_photo']['name'];
        $photoPath = $photoName; // Directly use $photoName

        // Move the uploaded file to the desired location
        if (move_uploaded_file($photoTemp, $photoPath)) {
            // Update pet data with the new photo path
            $updatePetQuery = "UPDATE Pet SET Pet_Name = ?, Pet_Breed = ?, Pet_Age = ?, Pet_Body = ?, Pet_Gender = ?, Pet_Condition = ?, Pet_Vaccinated = ?, Pet_Dewormed = ?, Pet_Neutered = ?, Pet_Date = ?, Pet_Location = ?, Pet_Description = ?, Pet_Photo = ? WHERE Pet_ID = ?";
            $stmt = mysqli_prepare($conn, $updatePetQuery);
            mysqli_stmt_bind_param($stmt, "sssssssssssssi", $petName, $petBreed, $petAge, $petBody, $petGender, $petCondition, $petVaccinated, $petDewormed, $petNeutered, $petDate, $petLocation, $petDescription, $photoPath, $petID);
        } else {
            // Handle file upload error
            echo "File upload failed.";
            exit();
        }
    } else {
        // If no new photo is uploaded, update pet data without changing the existing photo
        $updatePetQuery = "UPDATE Pet SET Pet_Name = ?, Pet_Breed = ?, Pet_Age = ?, Pet_Body = ?, Pet_Gender = ?, Pet_Condition = ?, Pet_Vaccinated = ?, Pet_Dewormed = ?, Pet_Neutered = ?, Pet_Date = ?, Pet_Location = ?, Pet_Description = ? WHERE Pet_ID = ?";
        $stmt = mysqli_prepare($conn, $updatePetQuery);
        mysqli_stmt_bind_param($stmt, "ssssssssssssi", $petName, $petBreed, $petAge, $petBody, $petGender, $petCondition, $petVaccinated, $petDewormed, $petNeutered, $petDate, $petLocation, $petDescription, $petID);
    }

    if (mysqli_stmt_execute($stmt)) {
        // Check if any rows were affected during the update
        $affectedRows = mysqli_stmt_affected_rows($stmt);

        if ($affectedRows > 0) {
            // Redirect to the edit_detail.php page with a success query parameter
            header("Location: edit_detail.php?pet_id=" . $petID . "&update=success");
            exit();
        } else {
            // Log the values being updated for debugging
            error_log("No changes were made. Pet ID: $petID, Data: " . print_r($_POST, true));

            // Handle the case where no rows were affected (no actual update happened)
            $error_message = "No changes were made. Pet ID may not exist or data is unchanged.";
            header("Location: edit_detail.php?pet_id=" . $petID . "&error=nochanges&message=" . urlencode($error_message));
            exit();
        }
    } else {
        // Handle the error by logging and redirecting with an error parameter
        $error_message = "Pet update failed: " . mysqli_error($conn);
        error_log($error_message);
        header("Location: edit_detail.php?pet_id=" . $petID . "&error=petupdatefailed&message=" . urlencode($error_message));
        exit();
    }
}

// If the script reaches this point without handling the form submission, redirect to the edit_detail.php page
header("Location: edit_detail.php?pet_id=" . $petID . "");
exit();
?>
