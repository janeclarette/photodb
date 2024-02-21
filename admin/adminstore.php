<?php
session_start();
include("../include/config.php");

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];

    // Insert into the 'Admin' table
    $sql = "INSERT INTO Admin (Name, Phone_Number, Email, Username, Password) 
            VALUES ('$name', '$phone_number', '$email', '$username', '$password')";

    if (mysqli_query($conn, $sql)) {
        // Check if any rows were affected
        if (mysqli_affected_rows($conn) > 0) {
            // Data inserted successfully
            $_SESSION['message'] = 'Admin registration successful. You can now log in.';
            header("Location: /photodb/admin/login.php");
            exit();
        } else {
            // No rows were affected
            $_SESSION['message'] = 'Admin registration failed. No rows were inserted.';
        }
    } else {
        // Query execution failed
        $_SESSION['message'] = 'Admin registration failed. Error: ' . mysqli_error($conn);
    }
}

// Redirect back to registration page on failure
header("Location: /photodb/admin/adminregister.php");
exit();
?>
