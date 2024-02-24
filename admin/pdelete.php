<?php
include("../include/config.php");

// Check if the ID parameter is set in the URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete_id'])) {
    // Sanitize the input to prevent SQL injection
    $photographer_id = mysqli_real_escape_string($conn, $_GET['delete_id']);

    // Construct the delete query
    $deletePhotographerQuery = "DELETE FROM photographers WHERE PhotographerID = $photographer_id";

    // Perform the deletion and handle errors
    if (mysqli_query($conn, $deletePhotographerQuery)) {
        // If deletion is successful, redirect to photographers.php
        header("Location: photographers.php");
        exit();
    } else {
        // If there's an error, display an error message
        echo "Error deleting photographer: " . mysqli_error($conn);
    }
} else {
    // If the ID parameter is not set, display a message indicating that the photographer was not found
    echo "Photographer not found";
}

// Close the database connection
$conn->close();
?>
