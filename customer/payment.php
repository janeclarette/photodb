<?php
include("../include/config.php");

// Initialize variables to store values
$photographerID = $reservationDate = $timeID = $placeID = $customerPlaceID = $packageID = $price = '';

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

        // Use prepared statement to prevent SQL injection
        $stmt = mysqli_prepare($conn, $updateImageQuery);
        mysqli_stmt_bind_param($stmt, "si", $imgTransacFileName, $transactionID);

        if (mysqli_stmt_execute($stmt)) {
            // Update status to "6"
            $updateStatusQuery = "UPDATE transactions SET StatusID = 6 WHERE TransactionID = ?";
            $stmtStatus = mysqli_prepare($conn, $updateStatusQuery);
            mysqli_stmt_bind_param($stmtStatus, "i", $transactionID);

            if (mysqli_stmt_execute($stmtStatus)) {
                echo '<script>
                    alert("Successful Transaction");
                    window.location.href = "customerdashboard.php";
                  </script>';
            } else {
                echo "Error updating status: " . mysqli_error($conn);
            }

            // Close the prepared statement for status update
            mysqli_stmt_close($stmtStatus);
        } else {
            echo "Error updating image: " . mysqli_error($conn);
        }

        // Close the prepared statement for image update
        mysqli_stmt_close($stmt);
    }

    // Fetch additional data if needed
    $transactionQuery = "SELECT * FROM transactions WHERE TransactionID = ?";
    $stmt = mysqli_prepare($conn, $transactionQuery);
    mysqli_stmt_bind_param($stmt, "i", $transactionID);
    mysqli_stmt_execute($stmt);

    // Get the result and check if the query was successful
    $transactionResult = mysqli_stmt_get_result($stmt);
    if ($transactionResult && mysqli_num_rows($transactionResult) > 0) {
        $transactionRow = mysqli_fetch_assoc($transactionResult);

        // Assign fetched values to variables
        $photographerID = $transactionRow['PhotographerID'];
        $reservationDate = $transactionRow['ReservationDate'];
        $timeID = $transactionRow['Time_ID'];
        $placeID = $transactionRow['PlaceID'];
        $customerPlaceID = $transactionRow['CustomerPlaceID'];
        $packageID = $transactionRow['PackageID'];

        // Fetch price based on the PackageID from Packages table
        $packageQuery = "SELECT Price FROM Packages WHERE PackageID = ?";
        $stmt = mysqli_prepare($conn, $packageQuery);
        mysqli_stmt_bind_param($stmt, "i", $packageID);
        mysqli_stmt_execute($stmt);

        // Get the result and check if the query was successful
        $packageResult = mysqli_stmt_get_result($stmt);
        if ($packageResult && mysqli_num_rows($packageResult) > 0) {
            $packageRow = mysqli_fetch_assoc($packageResult);
            $price = $packageRow['Price'];

            // Calculate admin fee (assuming 10%)
            $adminFee = $price * 0.10;

            // Update admin fee and photographer earning
            $updateTransactionQuery = "UPDATE transactions SET AdminFee = ?, PhotographerEarning = ? WHERE TransactionID = ?";
            $stmtUpdateTransaction = mysqli_prepare($conn, $updateTransactionQuery);
            $photographerEarning = $price - $adminFee;

            mysqli_stmt_bind_param($stmtUpdateTransaction, "ddi", $adminFee, $photographerEarning, $transactionID);

            if (mysqli_stmt_execute($stmtUpdateTransaction)) {
                // echo "10% of the Price will serve as the Admin Fee";
            } else {
                echo "Error updating transaction: " . mysqli_error($conn);
            }

            // Close the prepared statement for updating transaction
            mysqli_stmt_close($stmtUpdateTransaction);
        } else {
            echo "Error fetching package data: " . mysqli_error($conn);
        }
    } else {
        echo "Error fetching transaction data: " . mysqli_error($conn);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
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
                <label for="photographerid">Transaction ID:</label><br>
                <input type="text" id="TransactionID" name="TransactionID" class="form-control" value="<?php echo $transactionID; ?>" required><br>
            </div><br>
   

             <div class="form-group">
                <label for="photographerid">Photographer ID:</label><br>
                <input type="text" id="photographerid" name="photographerid" class="form-control" value="<?php echo $photographerID; ?>" required><br>
            </div><br>
            <div class="form-group">
                <label for="reservation_date">Reservation Date:</label><br>
                <input type="text" id="reservation_date" name="reservation_date" class="form-control" value="<?php echo $reservationDate; ?>" required><br>
            </div><br>
            <div class="form-group">
                <label for="time_id">Time ID:</label><br>
                <input type="text" id="time_id" name="time_id" class="form-control" value="<?php echo $timeID; ?>" required><br>
            </div>
        </div><br>
        <div class="form-row">
            <div class="form-group">
                <label for="place_id">Place ID:</label><br>
                <input type="text" id="place_id" name="place_id" class="form-control"  value="<?php echo $placeID; ?>" ><br>
            </div>
            <div class="form-group">
                <label for="customer_place_id">Customer Place ID:</label><br>
                <input type="text" id="customer_place_id" name="customer_place_id" class="form-control" value="<?php echo $customerPlaceID; ?>" ><br>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="package_id">Package ID:</label><br>
                <input type="text" id="package_id" name="package_id" class="form-control" value="<?php echo $packageID; ?>" required><br>
            </div>
            <div class="form-group">
                <label for="price">Price:</label><br>
                <input type="text" id="price" name="price" class="form-control" value="<?php echo $price; ?>" required><br>

            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="img_transac">Transaction Image:</label><br>
                <input type="file" id="img_transac" name="img_transac" class="form-control"><br>
            </div>
        </div>
        <input type="submit" value="SEND PAYMENT" name="payment" class="btn">
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
        max-width: 1000px; /* Adjust the max-width of the form */
        margin: 0 auto; /* Center the form horizontally */
        padding: 40px;
        background-color: rgba(255, 255, 255, 0.7); /* Adjust the background color and opacity */
        border-radius: 10px;
    }

    .form-outline label {
        font-size: 1.2rem; /* Adjust the font size of labels */
        font-weight: bold;
        color: #333; /* Adjust the color of labels */
    }

    .form-control {
        padding: 10px;
        margin-top: 10px;
        border-radius: 5px;
        border: 1px solid #ccc; /* Adjust the border color */
        width: 100%; /* Adjust the width of form controls */
    }

    .form-control:focus {
        outline: none;
        border-color: #B0A695; /* Adjust the border color on focus */
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
        margin: 30px auto; /* Center the button horizontally */
        width: 300px;
    }

    .btn:hover {
        background-color: #9B8E7B; /* Adjust the background color on hover */
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .form-group {
        flex: 0 0 calc(50% - 20px); /* Two columns with a gap of 20px */
        margin-right: 40px;
    }

    .form-group:last-child {
        margin-right: 0; /* Remove right margin for the last column */
    }
</style>
