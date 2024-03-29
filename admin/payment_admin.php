<?php
// Start the session
// Include necessary files and establish a database connection
include("../include/config.php");

// Initialize variables to store values
$customerName = $photographerName = $packageName = $packagePrice = $adminFee = $photographerEarning = $statusName = $gcashNumber = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['payment'])) {
    // Retrieve transaction ID from the form submission
    $transactionID = isset($_POST['TransactionID']) ? $_POST['TransactionID'] : '';

    // Check if 'img_transac' key exists in the $_FILES array
    if (isset($_FILES['img_admin'])) {
        // Upload image file
        $imgTransacFileName = $_FILES['img_admin']['name'];
        $imgTransacTempName = $_FILES['img_admin']['tmp_name'];
        $imgTransacTarget = "../uploads/";

        // Move the uploaded image to the specified directory with the original file name
        move_uploaded_file($imgTransacTempName, $imgTransacTarget . $imgTransacFileName);

        // Update image file name into Transactions table
        $updateImageQuery = "UPDATE transactions SET img_admin = ? WHERE TransactionID = ?";

        // Use prepared statement to prevent SQL injection
        $stmt = mysqli_prepare($conn, $updateImageQuery);
        mysqli_stmt_bind_param($stmt, "si", $imgTransacFileName, $transactionID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        echo '<script>
            alert("Successful Transaction");
            window.location.href = "transaction.php";
        </script>';
    }

    $sql = "SELECT 
        C.Name AS CustomerName,
        P.Name AS PhotographerName,
        Pa.PackageName,
        Pa.Price AS PackagePrice,
        T.AdminFee,
        T.PhotographerEarning,
        T.StatusID,
        TS.StatusName,
        CASE
            WHEN T.StatusID = 2 THEN P.Gcash_number
            WHEN T.StatusID = 3 THEN C.Gcash_number
        END AS GcashNumber,
        ROUND(Pa.Price * 0.10, 2) AS CalculatedAdminFee  -- Calculate Admin Fee as 10% of the Package Price with 2 decimal places
    FROM Transactions T
    INNER JOIN Customers C ON T.CustomerID = C.CustomerID
    INNER JOIN Photographers P ON T.PhotographerID = P.PhotographerID
    INNER JOIN Packages Pa ON T.PackageID = Pa.PackageID
    INNER JOIN TransactionStatus TS ON T.StatusID = TS.StatusID
    WHERE T.TransactionID = ?";

    // Use prepared statement to prevent SQL injection
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $transactionID);
        mysqli_stmt_execute($stmt);

        // Get result set
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $customerName = $row['CustomerName'];
            $photographerName = $row['PhotographerName'];
            $packageName = $row['PackageName'];
            $packagePrice = $row['PackagePrice'];
            $adminFee = number_format($row['CalculatedAdminFee'], 2);  // Use the calculated Admin Fee with 2 decimal places
            $photographerEarning = ($packagePrice - $row['CalculatedAdminFee']);  // Calculate Photographer Earning with 2 decimal places
            $gcashNumber = $row['GcashNumber'];
            $statusName = $row['StatusName'];

            // Conditionally set adminFee and photographerEarning
            if ($row['StatusID'] == 3) {
                $adminFee = '0.00';
                $photographerEarning = '0.00';
            }
        } else {
            echo "Error: Transaction not found.";
            // Handle the case where the transaction is not found
        }

        // Close the result set
        mysqli_stmt_close($stmt);

        // Update AdminFee and PhotographerEarning columns in the transactions table
        $updateTransactionQuery = "UPDATE transactions SET AdminFee = ?, PhotographerEarning = ? WHERE TransactionID = ?";
        $stmtUpdate = mysqli_prepare($conn, $updateTransactionQuery);
        mysqli_stmt_bind_param($stmtUpdate, "ddi", $adminFee, $photographerEarning, $transactionID);
        mysqli_stmt_execute($stmtUpdate);
        mysqli_stmt_close($stmtUpdate);
    } else {
        // Query error
        echo "Error: " . mysqli_error($conn);
    }

            if (isset($_POST['payment'])) {
            // Update status to 7 (Successful)
            $updateStatusQuery = "UPDATE transactions SET StatusID = 7 WHERE TransactionID = ?";
            $stmtStatus = mysqli_prepare($conn, $updateStatusQuery);
            mysqli_stmt_bind_param($stmtStatus, "i", $transactionID);
            mysqli_stmt_execute($stmtStatus);
            mysqli_stmt_close($stmtStatus);
        }

    // Close the database connection
    mysqli_close($conn);
}
?>
<!-- Rest of your HTML code -->

<!-- Rest of your HTML code -->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">

</head>

<body>
    <h2>Payment Admin</h2>
    <div class="form-outline">
        <form action="" method="post" enctype="multipart/form-data">
            <?php
            // Display fetched data in the form
            echo "<div class='form-group'>
            <label for='name'>Transaction ID:</label><br>
            <input type='text' id='TransactionID' name='TransactionID' class='form-control' value='{$transactionID}' readonly>
        </div>";
            echo "<div class='form-group'>
                        <label for='name'>Customer Name:</label><br>
                        <input type='text' id='name' name='name' class='form-control' value='{$customerName}' readonly>
                    </div>";

            echo "<div class='form-group'>
                        <label for='username'>Photographer Name:</label><br>
                        <input type='text' id='username' name='username' class='form-control' value='{$photographerName}' readonly>
                    </div>";

            echo "<div class='form-group'>
                        <label for='package'>Package Name:</label><br>
                        <input type='text' id='package' name='package' class='form-control' value='{$packageName}' readonly>
                    </div>";

            echo "<div class='form-group'>
                        <label for='price'>Package Price:</label><br>
                        <input type='text' id='price' name='price' class='form-control' value='{$packagePrice}' readonly>
                    </div>";

            // Fetch additional data similar to payment.php
            // ...

            echo "<div class='form-group'>
                        <label for='adminFee'>Admin Fee:</label><br>
                        <input type='text' id='adminFee' name='adminFee' class='form-control' value='{$adminFee}' readonly>
                    </div>";

            echo "<div class='form-group'>
                        <label for='photographerEarning'>Photographer Earning:</label><br>
                        <input type='text' id='photographerEarning' name='photographerEarning' class='form-control' value='{$photographerEarning}' readonly>
                    </div>";

            echo "<div class='form-group'>
                    <label for='name'>Status:</label><br>
                    <input type='text' id='statusid' name='statusid' class='form-control' value='{$statusName}' readonly>
                </div>";

            echo "<div class='form-group'>
                        <label for='gcash'>Gcash Number:</label><br>
                        <input type='text' id='gcash' name='gcash' class='form-control' value='{$gcashNumber}' readonly>
                    </div>";
            

            echo "<div class='form-row'>
                    <div class='form-group'>
                        <label for='img_admin'>Transaction Image:</label><br>
                        <input type='file' id='img_admin' name='img_admin' class='form-control'><br>
                    </div>
                </div>";
            ?>
            <input type="submit" name="payment" value="Send Payment" class="btn">
        </form>
    </div>
</body>

</html>

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
