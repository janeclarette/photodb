<?php
// Start the session
session_start();
// Include necessary files and establish a database connection
include("../include/config.php");

include("../customer/header.php");
// Check if customerID is set in the session
if (!isset($_SESSION['CustomerID'])) {
    // Redirect to login page or handle the case when the customer is not logged in
    header("Location: ../customer/login.php");
    exit();
}

// Retrieve customerID from the session
$customerID = $_SESSION['CustomerID'];

// Check if TransactionID is set in the query parameters
if (!isset($_GET['TransactionID'])) {
    // Redirect or display an error message if TransactionID is not provided
    echo "Transaction ID is missing.";
    exit();
}

// Retrieve TransactionID from the query parameters
$transactionID = $_GET['TransactionID'];

// Fetch data from the Transactions table based on TransactionID
$query = "SELECT t.TransactionID, t.PhotographerID, pt.Name AS PhotographerName
            FROM Transactions t
            JOIN photographers pt ON t.PhotographerID = pt.PhotographerID
            WHERE t.CustomerID = $customerID AND t.TransactionID = $transactionID";

$result = mysqli_query($conn, $query);

// Check if the query execution was successful
if ($result) {
    // Fetch transaction data
    $row = mysqli_fetch_assoc($result);
    $photographerID = $row['PhotographerID'];
    $photographerName = $row['PhotographerName'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Photographer</title>
</head>
<body>
    <h2>Review Photographer: <?php echo $photographerName; ?></h2>
    <form action="submit_review.php" method="post">
        <input type="hidden" name="TransactionID" value="<?php echo $transactionID; ?>">
        <input type="hidden" name="PhotographerID" value="<?php echo $photographerID; ?>">
        <div>
            <label for="rating">Rating:</label>
            <select name="rating" id="rating" required>
                <option value="5">5 - Excellent</option>
                <option value="4">4 - Very Good</option>
                <option value="3">3 - Good</option>
                <option value="2">2 - Fair</option>
                <option value="1">1 - Poor</option>
            </select>
        </div>
        <div>
            <label for="comment">Comment:</label><br>
            <textarea name="comment" id="comment" cols="30" rows="5" required></textarea>
        </div>
        <div>
            <button type="submit" name="submit_review">Submit Review</button>
        </div>
    </form>
</body>
</html>

<?php
} else {
    // Handle query error
    echo "Error: " . mysqli_error($conn);
}
?>
