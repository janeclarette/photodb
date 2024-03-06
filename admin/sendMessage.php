<?php
// Include the database connection
include("../include/config.php");

// Start the session
session_start();

// Check if the form is submitted
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the sender ID from the session
    $senderID = $_SESSION['AdminID']; // Adjust the session variable name if needed

    // Get other form data
    $receiverType = $_POST['receiverType'];
    $receiverID = $_POST['receiverID'];
    $message = $_POST['message'];

    // Handle image upload
    $imagePath = null;
    if ($_FILES['image']['error'] === 0) {
        $uploadsDirectory = "../uploads/";
        $uploadedFile = $uploadsDirectory . basename($_FILES['image']['name']);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadedFile)) {
            $imagePath = $uploadedFile;
        } else {
            echo "Error uploading image.";
            exit;
        }
    }

    // Insert the message into the database
    $query = "INSERT INTO Messages (SenderID, ReceiverID, MessageType, Body, img_message) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "iisss", $senderID, $receiverID, $receiverType, $message, $imagePath);

        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Check if the insertion was successful
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo '<script>alert("Message sent successfully!");</script>';
            echo '<script>window.location.href = "message.php";</script>';
        } else {
            echo "Error sending message. Possibly invalid ReceiverID.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement.";
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // If the form is not submitted, redirect or handle accordingly
    echo "Form not submitted.";
}
?>
