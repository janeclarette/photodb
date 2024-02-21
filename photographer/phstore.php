<?php
session_start();
include("../include/config.php");

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $city_id = $_POST['city_id'];
    $email = $_POST['email'];
    $img_photographer = isset($_FILES['img_photographer']) ? $_FILES['img_photographer'] : null;
    $role = 'photographer'; // Assuming all registrations are for photographers

    // Handle image upload
    $targetDir = "uploads/";
    $img_path = null;

    if ($img_photographer !== null) {
        $img_path = $targetDir . basename($img_photographer['name']);
        $img_type = strtolower(pathinfo($img_path, PATHINFO_EXTENSION));

        // Check if the file is an actual image
        $check = getimagesize($img_photographer['tmp_name']);
        if ($check === false) {
            $_SESSION['message'] = 'Invalid image file.';
            header("Location: phregister.php");
            exit();
        }

        // Allow certain file formats
        $allowedFormats = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($img_type, $allowedFormats)) {
            $_SESSION['message'] = 'Only JPG, JPEG, PNG, and GIF files are allowed.';
            header("Location: phregister.php");
            exit();
        }

        // Move the uploaded file to the desired directory
        if (!move_uploaded_file($img_photographer['tmp_name'], $img_path)) {
            $_SESSION['message'] = 'Error uploading image.';
            header("Location: phregister.php");
            exit();
        }
    }

    // Insert into the 'photographers' table
    $sql = "INSERT INTO photographers (Name, Phone_Number, Address, CityID, Email, Username, Password, img_photographer, Role) 
            VALUES ('$name', '$phone_number', '$address', '$city_id', '$email', '$username', '$password', '$img_path', '$role')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = 'Photographer registration successful. You can now log in as a photographer.';
        header("Location: /photodb/admin/login.php"); // Change the redirect location as needed
        exit();
    } else {
        $_SESSION['message'] = 'Photographer registration failed. Error: ' . mysqli_error($conn);
        header("Location: phregister.php");
        exit();
    }
} else {
    $_SESSION['message'] = 'Invalid form submission.';
    header("Location: phregister.php");
    exit();
}
?>
