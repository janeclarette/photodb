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
            T.gcash_admin,
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
                <th>Admin Gcash</th>
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
                <td>{$row['gcash_admin']}</td>
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



<title>Customer Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <body>
    </body>
    </html>

    <!-- Add your CSS stylesheets here -->
    <style>
        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4F709C;
            color: white;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        /* Button styles */
        .payment-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            background-color: #4F709C;
            color: white;
            font-size: 14px;
            cursor: pointer;
        }

        .payment-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .confirm-btn, .decline-btn {
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            color: white;
            font-size: 14px;
        }

        .confirm-btn {
            background-color: #4CAF50;
        }

        .decline-btn {
            background-color: #f44336;
        }

        .confirm-btn:hover, .decline-btn:hover {
            opacity: 0.8;
        }

        .review-btn {
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            background-color: #4F709C;
            color: white;
            font-size: 14px;
        }

        .review-btn:hover {
            opacity: 0.8;
        }
    </style>