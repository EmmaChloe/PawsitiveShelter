<?php
session_start(); // Start the session

if (isset($_POST['adduser'])) {
    require 'dbConn.php';

    // Retrieve form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Use bcrypt for password hashing

    // Perform validation checks
    if (empty($name) || empty($email) || empty($phone) || empty($username) || empty($password)) {
        header("Location: edit_public.php?error=emptyfields");
        exit();
    } else {
        // Check if the username is already taken
        $sql = "SELECT User_Name FROM User WHERE User_Username=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "SQL Error in SELECT: " . mysqli_stmt_error($stmt);
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            if ($resultCheck > 0) {
                header("Location: edit_public.php?error=usernametaken");
                exit();
            } else {
                // Insert user data into the database
                $sql = "INSERT INTO User (User_Name, User_Email, User_Phone, User_Username, User_Password) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo "SQL Error in INSERT: " . mysqli_stmt_error($stmt);
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $phone, $username, $hashedPassword);
                    // Execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        // Close the prepared statement
                        mysqli_stmt_close($stmt);

                        // Set success message
                        $_SESSION['success_message'] = "User added successfully.";

                        // Redirect to the appropriate page after successful addition
                        header("Location: edit_public.php");
                        exit();
                    } else {
                        // Log the error instead of displaying it to the user
                        error_log("Error adding record: " . mysqli_error($conn));
                        echo "Error adding record. Please try again later.";
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
} else {
    header("Location: edit_public.php?error=tryagain");
    exit();
}
?>
