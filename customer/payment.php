<?php
include("../include/config.php");

// Initialize variables to store values
$transactionID = $photographerID = $reservationDate = $timeID = $placeID = $customerPlaceID = $packageID = $price = '';

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
                                pl.placename, 
                                pk.packagename, 
                                pk.price,
                                tm.time_id, 
                                t.CustomerPlaceID
                         FROM transactions t
                         JOIN photographers ph ON t.PhotographerID = ph.PhotographerID
                         JOIN places pl ON t.PlaceID = pl.PlaceID
                         JOIN packages pk ON t.PackageID = pk.PackageID
                         JOIN time tm ON t.Time_ID = tm.Time_ID
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
                $placeName = $transactionRow['placename'];
                $packageName = $transactionRow['packagename'];
                $price = $transactionRow['price'];
                $time = $transactionRow['time_id'];
                $customerPlaceID = $transactionRow['CustomerPlaceID'];
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
            <label for="TransactionID">Transaction ID:</label><br>
            <input type="text" id="TransactionID" name="TransactionID" class="form-control" value="<?php echo $transactionID; ?>" required><br>
        </div><br>
        <div class="form-group">
            <label for="photographerName">Photographer ID:</label><br>
            <input type="text" id="photographername" name="photographername" class="form-control" value="<?php echo $photographerName; ?>" required><br>
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
            <label for="placeName">Place ID:</label><br>
            <input type="text" id="placename" name="placename" class="form-control"  value="<?php echo $placeName; ?>" ><br>
        </div>
        <div class="form-group">
            <label for="customer_place_id">Customer Place ID:</label><br>
            <input type="text" id="customer_place_id" name="customer_place_id" class="form-control" value="<?php echo $customerPlaceID; ?>" ><br>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="packageName">Package ID:</label><br>
            <input type="text" id="packagename" name="packagename" class="form-control" value="<?php echo $packageName; ?>" required><br>
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
