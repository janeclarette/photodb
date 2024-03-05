<?php

include("../include/config.php");

// Initialize variables to store values
$transactionID = $photographerID = $reservationDate = $timeID = $placeID = $customerPlaceID = $packageID = $price = '';
$gcashNumbers = array(); // Initialize an array to store Gcash numbers

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['payment'])) {
    // Retrieve transaction ID from the form submission
    $transactionID = $_POST['TransactionID'];

    // Check if 'img_transac' key exists in the $_FILES array
    if (isset($_FILES['img_transac'])) {
        // Upload image file
        $imgTransacFileName = $_FILES['img_transac']['name'];
        $imgTransacTempName = $_FILES['img_transac']['tmp_name'];
        $imgTransacTarget = "../uploads/";

        // Move the uploaded image to the specified directory with the original file name
        move_uploaded_file($imgTransacTempName, $imgTransacTarget . $imgTransacFileName);

        // Update image file name into Transactions table
        $updateImageQuery = "UPDATE transactions SET img_transac = ? WHERE TransactionID = ?";
        $stmt = mysqli_prepare($conn, $updateImageQuery);
        mysqli_stmt_bind_param($stmt, "si", $imgTransacFileName, $transactionID);

        if (mysqli_stmt_execute($stmt)) {
            // Update status to "6"
            $updateStatusQuery = "UPDATE transactions SET StatusID = 6 WHERE TransactionID = ?";
            $stmtStatus = mysqli_prepare($conn, $updateStatusQuery);
            mysqli_stmt_bind_param($stmtStatus, "i", $transactionID);

            if (mysqli_stmt_execute($stmtStatus)) {
                // Get the selected Gcash number from the form
                $selectedGcashNumber = $_POST['gcash_number_admin'];

                // Update the Gcash number in the transactions table
                $updateGcashTransactionQuery = "UPDATE transactions SET gcash_admin = ? WHERE TransactionID = ?";
                $stmtUpdateGcashTransaction = mysqli_prepare($conn, $updateGcashTransactionQuery);

                if ($stmtUpdateGcashTransaction) {
                    mysqli_stmt_bind_param($stmtUpdateGcashTransaction, "si", $selectedGcashNumber, $transactionID);

                    if (mysqli_stmt_execute($stmtUpdateGcashTransaction)) {
                        echo "Gcash number updated successfully in the transactions table";
                    } else {
                        echo "Error updating Gcash number in the transactions table: " . mysqli_error($conn);
                    }

                    mysqli_stmt_close($stmtUpdateGcashTransaction);
                } else {
                    echo "Error preparing Gcash number update statement for transactions table: " . mysqli_error($conn);
                }

                echo '<script>
                    alert("Successful Transaction");
                    window.location.href = "customerdashboard.php";
                  </script>';
            } else {
                echo "Error updating status: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmtStatus);
        } else {
            echo "Error updating image: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }

    // Fetch transaction data along with photographer, place, package, time, and customer place details using JOIN
    $transactionQuery = "SELECT t.*, 
    ph.name AS photographer_name, 
    pl.PlaceName AS place_name, 
    cp.PlaceName AS customer_place_name, 
    pk.packagename, 
    pk.price,
    tm.time_id, 
    tm.start_time,
    tm.end_time,
    t.CustomerPlaceID,
    a.Gcash_Number
FROM transactions t
JOIN photographers ph ON t.PhotographerID = ph.PhotographerID
LEFT JOIN places pl ON t.PlaceID = pl.PlaceID
LEFT JOIN customerplaces cp ON t.CustomerPlaceID = cp.CustomerPlaceID
JOIN packages pk ON t.PackageID = pk.PackageID
JOIN time tm ON t.Time_ID = tm.Time_ID
JOIN admin a 
WHERE t.TransactionID = ?";


    $stmt = mysqli_prepare($conn, $transactionQuery);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $transactionID);
        if (mysqli_stmt_execute($stmt)) {
            $transactionResult = mysqli_stmt_get_result($stmt);
            if ($transactionResult && mysqli_num_rows($transactionResult) > 0) {
                $transactionRow = mysqli_fetch_assoc($transactionResult);
                $photographerID = $transactionRow['PhotographerID'];
                $reservationDate = $transactionRow['ReservationDate'];
                $timeID = $transactionRow['Time_ID'];
                $placeID = $transactionRow['PlaceID'];
                $customerPlaceID = $transactionRow['CustomerPlaceID'];
                $packageID = $transactionRow['PackageID'];
                $photographerName = $transactionRow['photographer_name'];
                $packageName = $transactionRow['packagename'];
                $price = $transactionRow['price'];
                $gcashNumber = $transactionRow['Gcash_Number'];

                // Display either place name or customer place name based on which one is not null
$placeName = $transactionRow['place_name'];
$customerPlaceName = $transactionRow['customer_place_name'];
$displayPlaceName = !empty($placeName) ? $placeName : $customerPlaceName;

// Display time_id, start_time, and end_time
$displayTimeID = $transactionRow['time_id'];
$startTime = $transactionRow['start_time'];
$endTime = $transactionRow['end_time'];
$displayTimeRange = date('H:i', strtotime($startTime)) . ' - ' . date('H:i', strtotime($endTime));

            } else {
                echo "No transaction data found for Transaction ID: " . $transactionID;
            }
        } else {
            echo "Error executing query: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }

    // Fetch Gcash numbers from the admin table
    $gcashNumbersQuery = "SELECT Gcash_Number FROM admin";
    $gcashNumbersResult = mysqli_query($conn, $gcashNumbersQuery);

    if ($gcashNumbersResult) {
        // Fetch all Gcash numbers into the array
        while ($row = mysqli_fetch_assoc($gcashNumbersResult)) {
            $gcashNumbers[] = $row['Gcash_Number'];
        }

        mysqli_free_result($gcashNumbersResult);
    } else {
        echo "Error fetching Gcash numbers: " . mysqli_error($conn);
    }
}

mysqli_close($conn); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUSTOMER PAYMENT</title>
    <link rel="stylesheet" href="style.css"> <!-- Include your CSS file -->
</head>
<body>
<h2>PAYMENT</h2>
<form action="" method="post" class="form-outline" enctype="multipart/form-data">
   <div class="form-row">
    <div class="form-group">
        <label for="TransactionID">Transaction ID:</label>
        <input type="text" id="TransactionID" name="TransactionID" class="form-control" value="<?php echo $transactionID; ?>" required>
    </div>

    <div class="form-group">
        <label for="photographerName">Photographer Name:</label>
        <input type="text" id="photographername" name="photographername" class="form-control" value="<?php echo $photographerName; ?>" required>
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="reservation_date">Reservation Date:</label>
        <input type="text" id="reservation_date" name="reservation_date" class="form-control" value="<?php echo $reservationDate; ?>" required>
    </div>

    <div class="form-group">
        <label for="time_id">Time Duration:</label>
        <input type="text" id="time_id" name="time_id" class="form-control" value="<?php echo $displayTimeRange; ?>" readonly>
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="packageName">Package Name:</label>
        <input type="text" id="packagename" name="packagename" class="form-control" value="<?php echo $packageName; ?>" required>
    </div>

    <div class="form-group">
        <label for="price">Price:</label>
        <input type="text" id="price" name="price" class="form-control" value="<?php echo $price; ?>" required>
    </div>
</div>


<div class="form-row">
    <div class="form-group">
        <label for="gcash_number_admin">Select Admin Gcash Number:</label>
        <select id="gcash_number_admin" name="gcash_number_admin" class="form-control" required>
            <?php
            // Output Gcash numbers as options in the dropdown
            foreach ($gcashNumbers as $gcashNumber) {
                echo "<option value='$gcashNumber'>$gcashNumber</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="place_or_customer_place">Photographer's Place/ Customer's Place:</label>
        <input type="text" id="place_or_customer_place" name="place_or_customer_place" class="form-control" value="<?php echo $displayPlaceName; ?>" readonly>
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label for="img_transac">Transaction Image:</label>
        <input type="file" id="img_transac" name="img_transac" class="form-control">
    </div>

    <div class="form-group">
        <input type="submit" value="SEND PAYMENT" name="payment" class="btn">
    </div>
</div>

</form>
</body>
</html>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">

<style>
    h2 {
        text-align: center;
        color: #F3EEEA;
        font-weight: bold;
        font-size: 6rem;
        font-family: 'Satisfy';
    }

    body {
        background-image: url('../uploads/b.jpg');
        background-size: cover;
        background-attachment: fixed;
        height: 100vh;
        margin-top: 30px;
        margin-left: 10px;
    }

    .form-outline {
        max-width: 1000px;
        margin: 0 auto;
        padding: 40px;
        background-color: rgba(255, 255, 255, 0.7);
        border-radius: 10px;
    }

    .form-outline label {
        font-size: 1.2rem;
        font-weight: bold;
        color: #333;
    }

    .form-control {
        padding: 10px;
        margin-top: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        width: 100%;
    }

    .form-control:focus {
        outline: none;
        border-color: #B0A695;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #4F709C;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        display: block;
        margin: 30px auto;
        width: 300px;
    }

    .btn:hover {
        background-color: #9B8E7B;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .form-group {
        flex: 0 0 calc(50% - 20px);
        margin-right: 40px;
    }

    .form-group:last-child {
        margin-right: 0;
    }
</style>
 <!-- eto rin -->