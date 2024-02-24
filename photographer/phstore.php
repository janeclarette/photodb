<?php
include("../include/config.php");


if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $city_id = $_POST['city_id'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);
    $img_photographer = ""; // initialize empty string for image path

    // Handle image upload
    if(isset($_FILES['img_photographer']) && $_FILES['img_photographer']['error'] === UPLOAD_ERR_OK) {
        $img_photographer = "../uploads/" . basename($_FILES["img_photographer"]["name"]);

        // Move the uploaded file to the desired directory
        if(move_uploaded_file($_FILES["img_photographer"]["tmp_name"], $img_photographer)) {
            // Insert into the 'photographers' table
            $sql = "INSERT INTO photographers (Name, Phone_Number, Address, CityID, Email, Username, Password, img_photographer) 
                    VALUES ('$name', '$phone_number', '$address', '$city_id', '$email', '$username', '$password', '$img_photographer')";
            if(mysqli_query($conn, $sql)) {
                $_SESSION['message'] = 'Photographer registration successful. You can now log in as a photographer.';
                header("Location: /photodb/admin/login.php");
                exit();
            } else {
                $_SESSION['message'] = 'Photographer registration failed. Please try again.';
                header("Location: phregister.php");
                exit();
            }
        } else {
            $_SESSION['message'] = 'Error uploading image.';
            header("Location: phregister.php");
            exit();
        }
    } else {
        $_SESSION['message'] = 'Error uploading image.';
        header("Location: phregister.php");
        exit();
    }
}
?>
