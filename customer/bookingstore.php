<?php
session_start();
$loggedInCustomerID = isset($_SESSION['CustomerID']) ? $_SESSION['CustomerID'] : null;

if (!$loggedInCustomerID) {
    header("Location: /photodb/customer/login.php"); 
    exit();
}

include("../include/config.php");

// Fetch booking information
$customerID = $loggedInCustomerID;
$packageID = isset($_POST['packageID']) ? $_POST['packageID'] : null;
$photographerID = isset($_POST['photographerID']) ? $_POST['photographerID'] : null;
$bookingDate = isset($_POST['bookingDate']) ? $_POST['bookingDate'] : null;
$bookingTime = isset($_POST['bookingTime']) ? $_POST['bookingTime'] : null;
$bookingLocation = isset($_POST['bookingLocation']) ? $_POST['bookingLocation'] : null;
$photographerPlaceID = null;
$customerPlaceID = null;

if ($bookingLocation == 'photographer') {
    $photographerPlaceID = isset($_POST['photographerLocation']) ? $_POST['photographerLocation'] : null;
} elseif ($bookingLocation == 'customer') {
    // Handle customer's place information if needed
}
// Debugging output
echo "Photographer ID: " . $photographerID . "<br>";

// Check if the photographer exists
$checkPhotographerQuery = "SELECT COUNT(*) AS count FROM photographers WHERE PhotographerID = ?";
$stmt = mysqli_prepare($conn, $checkPhotographerQuery);
mysqli_stmt_bind_param($stmt, "i", $photographerID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$photographerExists = $row['count'] > 0;

if (!$photographerExists) {
    echo "Booking failed: The photographer does not exist.";
    exit();
}

$checkPackageQuery = "SELECT * FROM packages WHERE PackageID = ?";
$stmt = mysqli_prepare($conn, $checkPackageQuery);
mysqli_stmt_bind_param($stmt, "i", $packageID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    // Package does not exist, handle the error
    echo "Booking failed: The selected package does not exist.";
    exit();
}
// Store booking data in the database
$insertTransactionQuery = "INSERT INTO Transactions (customerid, photographerid, reservationdate, reservationtime, placeid, customerplaceid, packageid, statusid) VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
$stmt = mysqli_prepare($conn, $insertTransactionQuery);
mysqli_stmt_bind_param($stmt, "iissssi", $loggedInCustomerID, $photographerID, $bookingDate, $bookingTime, $photographerPlaceID, $customerPlaceID, $packageID);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    // Redirect back to customerdashboard.php
    header("Location: /photodb/customer/customerdashboard.php"); 
    exit();
} else {
    // Handle booking failure
    echo "Booking failed: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
?>
