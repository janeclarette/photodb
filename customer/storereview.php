<?php
// Include necessary files and establish a database connection
include("../include/config.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['review'])) {
    // Retrieve data from the form
    $transactionID = $_POST['TransactionID'];
    $photographerID = $_POST['PhotographerID'];
    $rating = $_POST['rating'];
    $comments = $_POST['comments'];

    // Insert the review into the database
    $insertQuery = "INSERT INTO review (TransactionID, PhotographerID, Rate, Comment)
                    VALUES ('$transactionID', '$photographerID', '$rating', '$comments')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        // Review successfully inserted
        echo "Review submitted successfully!";
        // Redirect to a confirmation page or any other page as needed
        
        header("Location: appointment.php?review_success=true");
        exit();
        // exit();
    } else {
        // Handle insertion error
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // If the form is not submitted, redirect to the review page or handle accordingly
    header("Location: review.php");
    exit();
}
?>
