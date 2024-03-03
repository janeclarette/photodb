<?php
// Include necessary files and establish a database connection
include("../include/config.php");
include("../admin/adminheader.php"); // Include your database connection

// Fetch transactions data
$sql = "SELECT
            T.TransactionID,
            C.Name AS CustomerName,
            P.Name AS PhotographerName,
            T.ReservationDate,
            Ti.start_time,
            Ti.end_time,
            T.TransactionDate,
            COALESCE(CP.PlaceName, Pl.PlaceName) AS PlaceName,
            Pa.PackageName,
            TS.StatusName,
            T.img_transac,
            T.AdminFee,
            T.PhotographerEarning,
            T.img_admin
        FROM
            Transactions T
            INNER JOIN Customers C ON T.CustomerID = C.CustomerID
            INNER JOIN Photographers P ON T.PhotographerID = P.PhotographerID
            INNER JOIN Time Ti ON T.Time_ID = Ti.time_id
            LEFT JOIN CustomerPlaces CP ON T.CustomerPlaceID = CP.CustomerPlaceID
            LEFT JOIN Places Pl ON T.PlaceID = Pl.PlaceID
            INNER JOIN Packages Pa ON T.PackageID = Pa.PackageID
            INNER JOIN TransactionStatus TS ON T.StatusID = TS.StatusID";

$result = mysqli_query($conn, $sql);

if ($result) {
    // Output table header
    echo "<table border='1'>
            <tr>
                <th>Transaction ID</th>
                <th>Customer Name</th>
                <th>Photographer Name</th>
                <th>Reservation Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Transaction Date</th>
                <th>Place</th>
                <th>Package Name</th>
                <th>Status</th>
                <th>Transaction Image</th>
                <th>Admin Fee</th>
                <th>Photographer Earning</th>
                <th>Admin Image</th>
                <th>Action</th>
            </tr>";

    // Output data rows
    while ($row = mysqli_fetch_assoc($result)) {
        $transactionImage = "../uploads/" . $row['img_transac'];
        $adminImage = "../uploads/" . $row['img_admin'];

        // Determine if the "Payment" button should be clickable
        $paymentButton = ($row['StatusName'] == 'Completed' || $row['StatusName'] == 'Refund') ? '<form action="payment_admin.php" method="post">
        <input type="hidden" name="TransactionID" value="' . $row['TransactionID'] . '">
        <button type="submit" name="payment" class="payment-btn">Payment</button>
    </form>' : '';


        echo "<tr>
                <td>{$row['TransactionID']}</td>
                <td>{$row['CustomerName']}</td>
                <td>{$row['PhotographerName']}</td>
                <td>{$row['ReservationDate']}</td>
                <td>{$row['start_time']}</td>
                <td>{$row['end_time']}</td>
                <td>{$row['TransactionDate']}</td>
                <td>{$row['PlaceName']}</td>
                <td>{$row['PackageName']}</td>
                <td>{$row['StatusName']}</td>
                <td><img src='{$transactionImage}' alt='Transaction Image' style='max-width: 100px; max-height: 100px;'></td>
                <td>{$row['AdminFee']}</td>
                <td>{$row['PhotographerEarning']}</td>
                <td><img src='{$adminImage}' alt='Admin Image' style='max-width: 100px; max-height: 100px;'></td>
                <td>{$paymentButton}</td>
            </tr>";
    }

    // Close the table
    echo "</table>";

    // Free result set
    mysqli_free_result($result);
} else {
    // Query error
    echo "Error: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>

<!-- nakdnakwknadklwad -->