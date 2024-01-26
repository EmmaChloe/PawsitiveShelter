<?php
session_start();

// Display PHP errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection script
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

// Define the directory to store uploaded files
$photoDirectory = 'images/news';

// Process news add form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newsTitle = $_POST['add_news_title'];
    $newsContent = $_POST['publish_content'];
    $newsDate = $_POST['publish_date'];
    $newsAuthor = $_POST['add_news_author']; // Adjust this based on how you store user information

    // Check if a file was uploaded without errors
    if (isset($_FILES['publish_photo']) && $_FILES['publish_photo']['error'] == 0) {
        $photoTemp = $_FILES['publish_photo']['tmp_name'];
        $photoName = $_FILES['publish_photo']['name'];
        $photoPath = $photoDirectory . DIRECTORY_SEPARATOR . $photoName;

        // Move the uploaded file to the desired location
        if (move_uploaded_file($photoTemp, $photoPath)) {
            // Insert news data into the News table using prepared statements
            $insertNewsQuery = "INSERT INTO News (User_ID, Publish_Title, Publish_Content, Publish_Date, Author, Publish_Photo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertNewsQuery);
            mysqli_stmt_bind_param($stmt, "isssss", $user_details['User_ID'], $newsTitle, $newsContent, $newsDate, $newsAuthor, $photoPath);

            if (mysqli_stmt_execute($stmt)) {
                // Redirect to the same page with success query parameter
                header("Location: edit_news.php?news=success");
                exit();
            } else {
                // Handle the error by logging and redirecting with an error parameter
                $error_message = "News insertion failed: " . mysqli_error($conn);
                error_log($error_message);
                header("Location: edit_news.php?error=newsinsertfailed&message=" . urlencode($error_message));
                exit();
            }
        } else {
            // Handle file upload error
            echo "File upload failed.<br>";
        }
    } else {
        // If no photo is uploaded, handle it accordingly (you can choose to set a default photo or show an error)
        // Insert news data without the photo path
        $insertNewsQuery = "INSERT INTO News (User_ID, Publish_Title, Publish_Content, Publish_Date, Author) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertNewsQuery);
        mysqli_stmt_bind_param($stmt, "issss", $user_details['User_ID'],$newsTitle, $newsContent, $newsDate, $newsAuthor);

        if (mysqli_stmt_execute($stmt)) {
            // Redirect to the same page with success query parameter
            header("Location: edit_news.php?news=success");
            exit();
        } else {
            // Handle the error by logging and redirecting with an error parameter
            $error_message = "News insertion failed: " . mysqli_error($conn);
            error_log($error_message);
            header("Location: edit_news.php?error=newsinsertfailed&message=" . urlencode($error_message));
            exit();
        }
    }
}

// Check for success query parameter and display success message
if (isset($_GET['news']) && $_GET['news'] == 'success') {
    echo '<div class="alert alert-success" role="alert">News added successfully!</div>';
}
?>