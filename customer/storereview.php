<?php
include("../include/config.php");

// Start the session
session_start();

// Function to retrieve customer details from the database
function getCustomerDetails($customerID, $conn) {
    $query = "SELECT Name FROM Customers WHERE CustomerID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['Name'];
    } else {
        return null;
    }
}

// Check if the customer is logged in
if (isset($_SESSION['CustomerID'])) {
    // Get customer information from the session
    $customerID = $_SESSION['CustomerID'];

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review'])) {
        // Get the form data
        $rating = isset($_POST['rating']) ? $_POST['rating'] : '';
        $comments = isset($_POST['comments']) ? $_POST['comments'] : '';
        $displayCustomerName = isset($_POST['Name']) ? 1 : 0; // 1 if checked, 0 if not checked

        // Fetch customer name from the database
        $customerName = getCustomerDetails($customerID, $conn);

        // Display a message based on the checkbox value
        echo "<p>Review submitted successfully!</p>";
        if ($displayCustomerName) {
            echo "<p>Review submitted by: $customerName</p>";
        } else {
            echo "<p>Review submitted anonymously</p>";
        }

        // Insert the review into the Review table using prepared statements
        $insertReviewQuery = "INSERT INTO Review (CustomerID, PhotographerID, Rate, Comment, TransactionID)
                             VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertReviewQuery);
        $stmt->bind_param("iiisi", $customerID, $_POST['PhotographerID'], $rating, $comments, $_POST['TransactionID']);

        if ($stmt->execute()) {
            echo "<p>Review inserted into the database</p>";
        } else {
            echo "Error: " . $stmt->error;
        }
        
        // JavaScript to notify after submitting the review
        echo '<script>alert("Review submitted successfully!");</script>';
    }
    ?>
    <?php
} else {
    // Redirect to the login page or handle as needed
    header("Location: login.php");
    exit();
}
?>