<?php
session_start();
$loggedInCustomerID = isset($_SESSION['CustomerID']) ? $_SESSION['CustomerID'] : null;

if (!$loggedInCustomerID) {
    header("Location: /photodb/customer/login.php");
    exit();
}

include("../include/config.php");

$customerID = $loggedInCustomerID;
$packageID = isset($_POST['packageID']) ? $_POST['packageID'] : null;
$photographerID = isset($_POST['photographerID']) ? $_POST['photographerID'] : null;
$bookingDate = isset($_POST['bookingDate']) ? $_POST['bookingDate'] : null;
$bookingLocation = isset($_POST['bookingLocation']) ? $_POST['bookingLocation'] : null;
$photographerPlaceID = null;
$customerPlaceID = null;
$timeID = null;

if ($bookingLocation == 'photographer') {
    $photographerPlaceID = isset($_POST['photographerLocation']) ? $_POST['photographerLocation'] : null;
} elseif ($bookingLocation == 'customer') {
    $customerPlaceName = isset($_POST['customerPlaceName']) ? $_POST['customerPlaceName'] : null;
    $customerPlaceAddress = isset($_POST['customerPlaceAddress']) ? $_POST['customerPlaceAddress'] : null;

    $insertCustomerPlaceQuery = "INSERT INTO customerplaces (customerid, placename, address) VALUES (?, ?, ?)";
    $insertCustomerPlaceStmt = mysqli_prepare($conn, $insertCustomerPlaceQuery);
    mysqli_stmt_bind_param($insertCustomerPlaceStmt, "iss", $loggedInCustomerID, $customerPlaceName, $customerPlaceAddress);
    mysqli_stmt_execute($insertCustomerPlaceStmt);
    mysqli_stmt_close($insertCustomerPlaceStmt);

    $customerPlaceID = mysqli_insert_id($conn);
}

$selectTimeQuery = "SELECT time_id FROM time WHERE start_time <= ? AND end_time >= ?";
$stmt = mysqli_prepare($conn, $selectTimeQuery);
mysqli_stmt_bind_param($stmt, "ss", $startTime, $endTime);
list($startTime, $endTime) = explode(" - ", $_POST['bookingTime']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$timeID = $row['time_id'];
mysqli_stmt_close($stmt);

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

$checkAvailabilityQuery = "SELECT scheduleid FROM availability_time WHERE time_id = ?";
$stmt = mysqli_prepare($conn, $checkAvailabilityQuery);
mysqli_stmt_bind_param($stmt, "i", $timeID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "Booking failed: The selected time is not available.";
    exit();
}

$scheduleID = $row['scheduleid'];
mysqli_stmt_close($stmt);

$updateScheduleQuery = "UPDATE availability_schedule SET schedule_status_id = 2 WHERE scheduleid = ?";
$stmt = mysqli_prepare($conn, $updateScheduleQuery);
mysqli_stmt_bind_param($stmt, "i", $scheduleID);
mysqli_stmt_execute($stmt);

$insertTransactionQuery = "INSERT INTO Transactions (customerid, photographerid, reservationdate, placeid, customerplaceid, packageid, statusid, time_id) VALUES (?, ?, ?, ?, ?, ?, 1, ?)";
$stmt = mysqli_prepare($conn, $insertTransactionQuery);
mysqli_stmt_bind_param($stmt, "iisssii", $loggedInCustomerID, $photographerID, $bookingDate, $photographerPlaceID, $customerPlaceID, $packageID, $timeID);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    header("Location: /photodb/customer/customerdashboard.php");
    exit();
} else {
    echo "Booking failed: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
?>
