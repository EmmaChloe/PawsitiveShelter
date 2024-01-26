<?php
// Include the database connection file
include 'dbConn.php';

session_start();

if (!isset($_SESSION["username"])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Fetch user details from the database using $_SESSION["username"]
$username = $_SESSION["username"];
$fetch_user_query = "SELECT * FROM User WHERE User_Username = ?";
$stmt_user = mysqli_prepare($conn, $fetch_user_query);

mysqli_stmt_bind_param($stmt_user, "s", $username);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);

if (!$result_user) {
    die("Query failed: " . mysqli_error($conn));
}

$user_details = mysqli_fetch_assoc($result_user);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve pet data from the form
    $petName = mysqli_real_escape_string($conn, $_POST['pet-name']);
    $petBreed = mysqli_real_escape_string($conn, $_POST['pet-breed']);
    $petAge = mysqli_real_escape_string($conn, $_POST['pet-age']);
    $petBody = mysqli_real_escape_string($conn, $_POST['pet-body']);
    $petGender = mysqli_real_escape_string($conn, $_POST['pet-gender']);
    $petLocation = mysqli_real_escape_string($conn, $_POST['pet-location']);
    $petCondition = mysqli_real_escape_string($conn, $_POST['pet-condition']);
    $petVaccinated = mysqli_real_escape_string($conn, $_POST['pet-vaccinated']);
    $petDewormed = mysqli_real_escape_string($conn, $_POST['pet-dewormed']);
    $petNeutered = mysqli_real_escape_string($conn, $_POST['pet-neutered']);
    $rehomeName = mysqli_real_escape_string($conn, $_POST['rehome-name']);
    $rehomePhone = mysqli_real_escape_string($conn, $_POST['rehome-phone']);
    $rehomeEmail = mysqli_real_escape_string($conn, $_POST['rehome-email']);
    $petDescription = mysqli_real_escape_string($conn, $_POST['pet-description']);
    $petDate = date("Y-m-d"); // or get the date from your form

    // Check if file upload was successful
    if ($_FILES['pet-photo']['error'] == UPLOAD_ERR_OK) {
        // Process file uploadPet_Photo
        $petPhoto = mysqli_real_escape_string($conn, $_FILES['pet-photo']['name']);
        $tempName = $_FILES['pet-photo']['tmp_name'];

        // Check if the file is an image
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $fileType = mime_content_type($tempName);
        if (!in_array($fileType, $allowedTypes)) {
            // Handle invalid file type error
            header("Location: rehome_detail.php?error=invalidfiletype");
            exit();
        }

        // Specify the directory where you want to move the uploaded file
        $folder = 'images/adopt/' . $petPhoto;

        // Check if the directory is writable
        if (!is_writable('images/adopt/')) {
            // Handle directory not writable error
            header("Location: rehome_detail.php?error=directorynotwritable");
            exit();
        }

        // Move the uploaded file to the desired folder
        if (move_uploaded_file($tempName, $folder)) {
            // Insert pet data into Pet table using prepared statement
            $insert_rehome_query = "INSERT INTO Pet (User_ID, Pet_Name, Pet_Breed, Pet_Age, Pet_Body, Pet_Gender, Pet_Location, Pet_Condition, Pet_Vaccinated, Pet_Dewormed, Pet_Neutered, Pet_Description, Pet_Photo, Pet_Date)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt_insert_pet = mysqli_prepare($conn, $insert_rehome_query);

            mysqli_stmt_bind_param(
                $stmt_insert_pet,
                "isssssssssssss",
                $user_details['User_ID'],
                $petName,
                $petBreed,
                $petAge,
                $petBody,
                $petGender,
                $petLocation,
                $petCondition,
                $petVaccinated,
                $petDewormed,
                $petNeutered,
                $petDescription,
                $petPhoto,
                $petDate
            );

            mysqli_stmt_execute($stmt_insert_pet);

            // Check if the pet insertion was successful
            if (mysqli_stmt_affected_rows($stmt_insert_pet) > 0) {
                // Redirect to the same page with success query parameter
                header("Location: rehome_detail.php?pet=success");
                exit();
            } else {
                // Pet insertion failed
                $error_message = "Pet insertion failed: " . mysqli_error($conn);
                error_log($error_message); // Log the error
                header("Location: rehome_detail.php?error=petinsertfailed");
                exit();
            }
        } else {
            // Handle file move error
            header("Location: rehome_detail.php?error=filemovefailed");
            exit();
        }
    } else {
        // Handle file upload error
        header("Location: rehome_detail.php?error=fileuploadfailed");
        exit();
    }
}

// Close prepared statements
mysqli_stmt_close($stmt_user);
mysqli_stmt_close($stmt_insert_pet);
mysqli_close($conn);
?>
