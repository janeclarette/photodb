<?php
include("../include/config.php");
include("../include/alert.php");

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $gcash_number = $_POST['gcash_number'];
    $address = $_POST['address'];
    $city_id = $_POST['city_id'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);
    $img_customer = ""; // initialize empty string for image path

    // Handle image upload
    if(isset($_FILES['img_customer']) && $_FILES['img_customer']['error'] === UPLOAD_ERR_OK) {
        $img_customer = "../uploads/" . basename($_FILES["img_customer"]["name"]);

        // Move the uploaded file to the desired directory
        if(move_uploaded_file($_FILES["img_customer"]["tmp_name"], $img_customer)) {
            // Insert into the 'customers' table
            $sql = "INSERT INTO customers (Name, Phone_Number, Gcash_Number, Address, CityID, Email, Username, Password, img_customer) 
                    VALUES ('$name', '$phone_number', '$gcash_number', '$address', '$city_id', '$email', '$username', '$password', '$img_customer')";
            if(mysqli_query($conn, $sql)) {
                $_SESSION['message'] = 'Customer registration successful. You can now log in.';
                header("Location: /photodb/admin/login.php");
                exit();
            } else {
                $_SESSION['message'] = 'Customer registration failed. Please try again.';
                header("Location: customerregister.php");
                exit();
            }
        } else {
            $_SESSION['message'] = 'Error uploading image.';
            header("Location: customerregister.php");
            exit();
        }
    } else {
        $_SESSION['message'] = 'Error uploading image.';
        header("Location: customerregister.php");
        exit();
    }
}
?>