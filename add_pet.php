<?php
session_start();

require 'dbConn.php';

// Fetch user details from the database using $_SESSION["username"]
$username = $_SESSION["username"];
if (empty($username)) {
    // Redirect or handle the case when the user is not authenticated
    header("Location: login.php"); // Redirect to your login page
    exit();
}

$fetch_user_query = "SELECT * FROM User WHERE User_Username = ?";
$stmt_user = mysqli_prepare($conn, $fetch_user_query);

mysqli_stmt_bind_param($stmt_user, "s", $username);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);

if (!$result_user) {
    die("Query failed: " . mysqli_error($conn));
}

$user_details = mysqli_fetch_assoc($result_user);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize the input data
    $pet_name = mysqli_real_escape_string($conn, $_POST['pet_name']);
    $pet_breed = mysqli_real_escape_string($conn, $_POST['pet_breed']);
    $pet_age = mysqli_real_escape_string($conn, $_POST['pet_age']);
    $pet_body = mysqli_real_escape_string($conn, $_POST['pet_body']);
    $pet_gender = mysqli_real_escape_string($conn, $_POST['pet_gender']);
    $pet_condition = mysqli_real_escape_string($conn, $_POST['pet_condition']);
    $pet_vaccinated = mysqli_real_escape_string($conn, $_POST['pet_vaccinated']);
    $pet_dewormed = mysqli_real_escape_string($conn, $_POST['pet_dewormed']);
    $pet_neutered = mysqli_real_escape_string($conn, $_POST['pet_neutered']);
    $pet_description = mysqli_real_escape_string($conn, $_POST['pet_description']);
    $pet_location = mysqli_real_escape_string($conn, $_POST['pet_location']);
    $pet_date = mysqli_real_escape_string($conn, $_POST['pet_date']);
    $user_id = $user_details['User_ID'];  // Assuming you have a user ID in the session

    // Insert pet details into the Pet table
    $insert_pet_query = "INSERT INTO Pet (Pet_Name, Pet_Breed, Pet_Age, Pet_Body, Pet_Gender, Pet_Condition, Pet_Vaccinated, Pet_Dewormed, Pet_Neutered, Pet_Description, Pet_Location, User_ID, Pet_Date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_stmt_init($conn);

    // Check if a file was uploaded without errors
    if (isset($_FILES['pet_photo']) && $_FILES['pet_photo']['error'] == 0) {
        $photoTemp = $_FILES['pet_photo']['tmp_name'];
        $photoName = $_FILES['pet_photo']['name'];

        // Specify the directory where you want to move the uploaded file
        $photoDirectory = 'images/adopt';

        // Move the uploaded file to the desired location
        if (move_uploaded_file($photoTemp, $photoDirectory . DIRECTORY_SEPARATOR . $photoName)) {
            // If the file was uploaded successfully, update the query to include the photo column
            $insert_pet_query = "INSERT INTO Pet (Pet_Name, Pet_Breed, Pet_Age, Pet_Body, Pet_Gender, Pet_Condition, Pet_Vaccinated, Pet_Dewormed, Pet_Neutered, Pet_Description, Pet_Location, User_ID, Pet_Photo, Pet_Date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_stmt_init($conn);

            if (mysqli_stmt_prepare($stmt, $insert_pet_query)) {
                mysqli_stmt_bind_param($stmt, "ssssssssssssss", $pet_name, $pet_breed, $pet_age, $pet_body, $pet_gender, $pet_condition, $pet_vaccinated, $pet_dewormed, $pet_neutered, $pet_description, $pet_location, $user_id, $photoName, $pet_date);

                // Execute the statement
                if (mysqli_stmt_execute($stmt)) {
                    // Redirect to the same page with success query parameter
                    header("Location: edit_adopt.php?pet=success");
                    exit();
                } else {
                    // Handle the error by logging and redirecting with an error parameter
                    $error_message = "Pet insertion failed: " . mysqli_error($conn);
                    error_log($error_message);
                    header("Location: edit_adopt.php?error=petinsertfailed&message=" . urlencode($error_message));
                    exit();
                }
            } else {
                // Handle the case when the insert pet query preparation fails
                $error_message = "Insert pet query preparation failed: " . mysqli_error($conn);
                error_log($error_message);
                header("Location: edit_adopt.php?error=querypreparationfailed&message=" . urlencode($error_message));
                exit();
            }
        } else {
            // Handle file upload error
            echo "File upload failed.<br>";
        }
    } else {
        // If no photo is uploaded, handle it accordingly (you can choose to set a default photo or show an error)
        // Continue with the original query without the photo column
        if (mysqli_stmt_prepare($stmt, $insert_pet_query)) {
            mysqli_stmt_bind_param($stmt, "ssssssssssss", $pet_name, $pet_breed, $pet_age, $pet_body, $pet_gender, $pet_condition, $pet_vaccinated, $pet_dewormed, $pet_neutered, $pet_description, $pet_location, $user_id, $pet_date);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to the same page with success query parameter
                header("Location: edit_adopt.php?pet=success");
                exit();
            } else {
                // Handle the error by logging and redirecting with an error parameter
                $error_message = "Pet insertion failed: " . mysqli_error($conn);
                error_log($error_message);
                header("Location: edit_adopt.php?error=petinsertfailed&message=" . urlencode($error_message));
                exit();
            }
        } else {
            // Handle the case when the insert pet query preparation fails
            $error_message = "Insert pet query preparation failed: " . mysqli_error($conn);
            error_log($error_message);
            header("Location: edit_adopt.php?error=querypreparationfailed&message=" . urlencode($error_message));
            exit();
        }
    }
} else {
    // Redirect to the add pet page if accessed without a form submission
    header('Location: add_pet.php');
    exit();
}
?>
