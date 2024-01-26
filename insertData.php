<?php
// Include the database connection file
include 'dbConn.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/SMTP.php';

session_start();

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
    // Retrieve donation data from the form
    $donationName = mysqli_real_escape_string($conn, $_POST['name']);
    $donationEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $donationPhone = mysqli_real_escape_string($conn, $_POST['phone']);
    $donationDate = mysqli_real_escape_string($conn, $_POST['donation-date']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);

    // Insert donation data into Donation Entity using prepared statements
    $insert_donation_query = "INSERT INTO Donation (User_ID, Donation_Date, Payment_Amount, Donation_Name, Donation_Email, Donation_Phone)
                              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_donation_query);
    mysqli_stmt_bind_param($stmt, "isdsss", $user_details['User_ID'], $donationDate, $amount, $donationName, $donationEmail, $donationPhone);

    mysqli_stmt_execute($stmt);

    // Check if the donation insertion was successful
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Send payment receipt email
        sendPaymentReceipt($donationEmail, $amount, $donationName);

        // Redirect to the same page with success query parameter
        header("Location: donate_detail.php?donation=success");
        exit();
    } else {
        // Donation insertion failed
        $error_message = "Donation insertion failed: " . $conn->error;
        error_log($error_message);
        header("Location: donate_detail.php?error=donationinsertfailed&message=$error_message");
        exit();
    }
}

// Close prepared statements
mysqli_stmt_close($stmt_user);
mysqli_close($conn);

function sendPaymentReceipt($recipientEmail, $amount, $donationName)
{
    $subject = 'Payment Receipt - Pawsitive Shelter';
    $message = "Thank you for your donation of $amount. Your support means a lot to us.";
    $headers = "From: jxin2367@gmail.com\r\n";
    $headers .= "Reply-To: jxin2367@gmail.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Send the email
    if (mail($recipientEmail, $subject, $message, $headers)) {
        // Email sent successfully
        // Redirect to the same page with success query parameter
        header("Location: donate_detail.php?donation=success");
        exit();
    } else {
        // Email failed to send
        $error_message = "Email sending failed: " . error_get_last()['message'];
        error_log($error_message);
        header("Location: donate_detail.php?error=emailsendfailed&message=$error_message");
        exit();
    }
}

?>
