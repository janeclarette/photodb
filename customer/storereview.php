<?php
session_start(); // Start the session
include("../include/config.php");

// Retrieve the customer ID from the session
$customerID = $_SESSION['CustomerID'];

// Retrieve other form data
$transactionID = $_POST['TransactionID'];
$photographerID = $_POST['PhotographerID'];
$rating = $_POST['rating'];
$comments = $_POST['comments'];
$display = isset($_POST['Name']) ? 1 : 0; // Check if the checkbox is checked

// Insert the review into the database
$insertQuery = "INSERT INTO review (CustomerID, PhotographerID, TransactionID, Rate, Comment, DisplayCustomerName)
                VALUES ('$customerID', '$photographerID', '$transactionID', '$rating', '$comments', '$display')";
$insertResult = mysqli_query($conn, $insertQuery);

if ($insertResult) {
    // Review successfully inserted
    echo "Review submitted successfully!";
    // Redirect to a confirmation page or any other page as needed
    header("Location: appointment.php?review_success=true");
    exit();
} else {
    // Handle insertion error
    echo "Error: " . mysqli_error($conn);
}
?>
