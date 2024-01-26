<?php
session_start();

require 'dbConn.php';

// Ensure the user is logged in, adjust this according to your authentication mechanism
if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newsID = mysqli_real_escape_string($conn, $_POST['news_id']);
    $newsTitle = mysqli_real_escape_string($conn, $_POST['add_news_title']);
    $newsContent = mysqli_real_escape_string($conn, $_POST['publish_content']);
    $newsDate = mysqli_real_escape_string($conn, $_POST['publish_date']);
    $newsAuthor = mysqli_real_escape_string($conn, $_POST['add_news_author']);

    // Check if a new photo was uploaded
    if (isset($_FILES['publish_photo']) && $_FILES['publish_photo']['error'] == 0) {
        $photoTemp = $_FILES['publish_photo']['tmp_name'];
        $photoName = $_FILES['publish_photo']['name'];
        $photoPath = "images/news/" . $photoName;

        // Move the uploaded file to the desired location
        if (move_uploaded_file($photoTemp, $photoPath)) {
            // Update news data with the new photo path
            $updateNewsQuery = "UPDATE News SET Publish_Title = ?, Publish_Content = ?, Publish_Date = ?, Author = ?, Publish_Photo = ? WHERE News_ID = ?";
            $stmt = mysqli_prepare($conn, $updateNewsQuery);
            mysqli_stmt_bind_param($stmt, "sssssi", $newsTitle, $newsContent, $newsDate, $newsAuthor, $photoPath, $newsID);
        } else {
            // Handle file upload error
            $_SESSION['error_message'] = "File upload failed.";
            header("Location: edit_news.php?error=fileuploadfailed");
            exit();
        }
    } else {
        // If no new photo is uploaded, update news data without changing the existing photo
        $updateNewsQuery = "UPDATE News SET Publish_Title = ?, Publish_Content = ?, Publish_Date = ?, Author = ? WHERE News_ID = ?";
        $stmt = mysqli_prepare($conn, $updateNewsQuery);
        mysqli_stmt_bind_param($stmt, "ssssi", $newsTitle, $newsContent, $newsDate, $newsAuthor, $newsID);
    }

    if (mysqli_stmt_execute($stmt)) {
        // Check if any rows were affected during the update
        $affectedRows = mysqli_stmt_affected_rows($stmt);

        if ($affectedRows > 0) {
            // Set success message and redirect to the edit_news.php page with a success query parameter
            $_SESSION['success_message'] = "News updated successfully.";
            header("Location: edit_news.php?update=success");
            exit();
        } else {
            // Log the values being updated for debugging
            error_log("No changes were made. News ID: $newsID, Data: " . print_r($_POST, true));

            // Handle the case where no rows were affected (no actual update happened)
            $_SESSION['error_message'] = "No changes were made. News ID may not exist or data is unchanged.";
            header("Location: edit_news.php?error=nochanges");
            exit();
        }
    } else {
        // Handle the error by logging and redirecting with an error parameter
        $_SESSION['error_message'] = "News update failed: " . mysqli_error($conn);
        header("Location: edit_news.php?error=newsupdatefailed");
        exit();
    }
}

// If the script reaches this point without handling the form submission, redirect to the edit_news.php page
header("Location: edit_news.php");
exit();
?>
