<?php
if (isset($_POST['register'])) {
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
        header("Location: register.php?signup=emptyfields");
        exit();
    } else {
        // Check if the username is already taken
        $sql = "SELECT User_Name FROM User WHERE User_Username=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: register.php?signup=registrationfailed");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            if ($resultCheck > 0) {
                header("Location: register.php?signup=usernametaken");
                exit();
            } else {
                // Insert user data into the database
                $sql = "INSERT INTO User (User_Name, User_Email, User_Phone, User_Username, User_Password) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: register.php?signup=registrationfailed");
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $phone, $username, $hashedPassword);
                    if (mysqli_stmt_execute($stmt)) {
                        // Registration was successful, redirect to index.php with success message
                        header("Location: register.php?signup=success");
                        exit();
                    } else {
                        header("Location: register.php?signup=registrationfailed");
                        exit();
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
} else {
    header("Location: register.php?signup=tryagain");
    exit();
}
?>
