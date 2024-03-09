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

// ...

$selectTimeQuery = "SELECT time_id FROM time WHERE start_time <= ? AND end_time >= ?";
$stmt = mysqli_prepare($conn, $selectTimeQuery);

// Assuming the time format is HH:mm - HH:mm
list($startTime, $endTime) = explode(" - ", $_POST['bookingTime']);

mysqli_stmt_bind_param($stmt, "ss", $startTime, $endTime);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

// Debug output
echo "Debug: Query - $selectTimeQuery\n";
echo "Debug: StartTime - $startTime, EndTime - $endTime\n";

if (!$row || !isset($row['time_id'])) {
    echo "Booking failed: The selected time is not valid.";
    exit();
}

$timeID = $row['time_id'];

// Debug output
echo "Debug: TimeID - $timeID\n";

mysqli_stmt_close($stmt);

// ...


$checkPhotographerQuery = "SELECT COUNT(*) AS count FROM photographers WHERE PhotographerID = ?";
$stmt = mysqli_prepare($conn, $checkPhotographerQuery);
mysqli_stmt_bind_param($stmt, "i", $photographerID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$photographerExists = $row['count'] > 0;

// Check if the photographer exists
// Check if the photographer exists
// Check if the photographer exists
// Check if the photographer exists
if (!$photographerExists) {
    echo "Booking failed: The photographer does not exist.";
    exit();
}

// Convert the bookingDate to date_id (assuming it's in the same format as stored in the database)
$getDateIdQuery = "SELECT date_id FROM available_date WHERE avail_date = ?";
$stmt = mysqli_prepare($conn, $getDateIdQuery);
mysqli_stmt_bind_param($stmt, "s", $bookingDate);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row || !isset($row['date_id'])) {
    echo "Booking failed: Invalid date selected.";
    exit();
}

$dateID = $row['date_id'];
mysqli_stmt_close($stmt);

// Check availability and get scheduleid
$checkAvailabilityQuery = "SELECT at.scheduleid, t.time_id FROM availability_time at
    JOIN time t ON at.time_id = t.time_id
    JOIN availability_schedule a ON at.scheduleid = a.scheduleid
    WHERE t.time_id = ? AND a.date_id = ? AND a.PhotographerID = ?";
$stmt = mysqli_prepare($conn, $checkAvailabilityQuery);
mysqli_stmt_bind_param($stmt, "iis", $timeID, $dateID, $photographerID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

echo "Debug: Query - $checkAvailabilityQuery\n";
echo "Debug: TimeID - $timeID, DateID - $dateID, PhotographerID - $photographerID\n";

if (!$row || !isset($row['scheduleid'])) {
    echo "Booking failed: The selected time is not available.";
    exit();
}

$scheduleID = $row['scheduleid'];
mysqli_stmt_close($stmt);

// Update availability_schedule table
$updateScheduleQuery = "UPDATE availability_schedule SET schedule_status_id = 2 WHERE scheduleid = ?";
$stmt = mysqli_prepare($conn, $updateScheduleQuery);
mysqli_stmt_bind_param($stmt, "i", $scheduleID);
mysqli_stmt_execute($stmt);

// Update availability_time table
$updateAvailabilityTimeQuery = "UPDATE availability_time SET time_id = ? WHERE scheduleid = ?";
$stmt = mysqli_prepare($conn, $updateAvailabilityTimeQuery);
mysqli_stmt_bind_param($stmt, "ii", $timeID, $scheduleID);
mysqli_stmt_execute($stmt);

// Insert into Transactions table
$insertTransactionQuery = "INSERT INTO Transactions (customerid, photographerid, reservationdate, placeid, customerplaceid, packageid, statusid, time_id) VALUES (?, ?, ?, ?, ?, ?, 4, ?)";
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
