<?php
// Include necessary files and establish a database connection
include("../include/config.php");
// Start the session
session_start();
include("../customer/header.php");
// Check if customerID is set in the session
if (!isset($_SESSION['CustomerID'])) {
    // Redirect to login page or handle the case when the customer is not logged in
    echo '<script>window.location.href = "../admin/login.php";</script>';
    exit();
}

// Retrieve customerID from the session
$customerID = $_SESSION['CustomerID'];

// Fetch data from the Transactions table based on customerID
$query = "SELECT t.TransactionID, t.PhotographerID, t.ReservationDate, t.Time_ID,
            COALESCE(t.PlaceID, cp.CustomerPlaceID) AS LocationID,
            t.PackageID, t.StatusID, t.TransactionDate, pt.Name, tm.start_time,
            tm.end_time, COALESCE(p.PlaceName, cp.PlaceName, 'N/A') AS LocationName,
            pk.PackageName, pk.Price, ts.StatusName
            FROM Transactions t
            JOIN photographers pt ON t.PhotographerID = pt.PhotographerID
            JOIN time tm ON t.Time_ID = tm.Time_ID
            LEFT JOIN places p ON t.PlaceID = p.PlaceID
            LEFT JOIN customerplaces cp ON t.CustomerPlaceID = cp.CustomerPlaceID
            JOIN packages pk ON t.PackageID = pk.PackageID
            JOIN transactionstatus ts ON t.StatusID = ts.StatusID
            WHERE t.CustomerID = $customerID";

$result = mysqli_query($conn, $query);

// Check for query execution success
if ($result) {
    ?>
    <html>
    <head>
        <title>Customer Page</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
        <style>
            body{
                background-image: url('../uploads/cover.jpg');  
    background-size: cover;
    background-position: center bottom;
    opacity: 0.9;  /* Adjust the opacity to make the image less visible */
        }

            h4 {
                margin-top: 0px;
                text-align: center;
                font-size: 6rem;
                color: #333;
                font-family: 'Satisfy';
            }

            .cards-container {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 20px;
                margin-top: 50px;
                
            }

            .card {
                background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent white background */
    border: 2px solid rgba(255,255,255, 10);
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                padding: 20px;
                width: 650px;
                margin-bottom: 20px;
            }

            .card-content-divider {
            border-bottom: 1px solid #ccc;
            margin-bottom: 10px;
        }
            .card-header {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .card-content {
                margin-bottom: 10px;
            }

            .card-content span {
                display: block;
                margin-bottom: 10px;
            }

            .card-actions {
                display: flex;
                justify-content: space-between;
                align-items: center;
                
            }

            .action-btn {
                margin-top: 20px;
                padding: 8px 16px;
                border-radius: 4px;
                background-color: #4F709C;
                color: #ffffff;
                font-size: 14px;
                cursor: pointer;
                border: none;
            }

            .cancel-btn {
                background-color: #f44336;
            }

            .confirm-btn {
                background-color: #4CAF50;
            }

            .decline-btn {
                background-color: #f44336;
            }

            .review-btn {
                background-color: #4F709C;
            }

            .download-pdf-btn {
                background-color: #4F709C;
            }

            .action-btn:disabled {
                background-color: #cccccc;
                cursor: not-allowed;
            }

            .action-btn:not(:last-child) {
                margin-right: 10px;
            }

            .action-btn:hover {
                opacity: 0.8;
            }
        </style>
    </head>
    <body>
    <section class="background">
        <h4>Appointments</h4>
    </section>
    <div class="cards-container">
    <?php
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="card">
                <div class="card-header"><?php echo $row['TransactionID']; ?></div>
                <div class="card-content">
                    <span><strong>Photographer:</strong> <?php echo $row['Name']; ?></span>
                    <div class="card-content-divider"></div>
                    <span><strong>Reservation Date:</strong> <?php echo $row['ReservationDate']; ?></span>
                    <div class="card-content-divider"></div>
                    <span><strong>Time:</strong> <?php echo $row['start_time'] . ' - ' . $row['end_time']; ?></span>
                    <div class="card-content-divider"></div>
                    <span><strong>Transaction Date:</strong> <?php echo $row['TransactionDate']; ?></span>
                    <div class="card-content-divider"></div>
                    <span><strong>Place:</strong> <?php echo $row['PlaceName']; ?></span>
                    <div class="card-content-divider"></div>
                    <span><strong>Package Name:</strong> <?php echo $row['PackageName']; ?></span>
                    <div class="card-content-divider"></div>
                    <span><strong>Price:</strong> <?php echo $row['Price']; ?></span>
                    <div class="card-content-divider"></div>
                    <span><strong>Status:</strong> <?php echo $row['StatusName']; ?></span>
                    <div class="card-content-divider"></div>
                </div>
                <div class="card-actions">
                    <form action="payment.php" method="post">
                        <input type="hidden" name="TransactionID" value="<?php echo $row['TransactionID']; ?>">
                        <button type="submit" name="payment" class="action-btn" <?php echo $row['StatusID'] == 4 ? '' : 'disabled'; ?>>
                            Payment
                        </button>
                    </form>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="TransactionID" value="<?php echo $row['TransactionID']; ?>">
                        <button type="submit" name="cancel" class="action-btn cancel-btn" onclick="return confirm('Are you sure you want to cancel this transaction?');"
                            <?php echo $row['StatusID'] == 4 ? '' : 'disabled'; ?>>
                            Cancel
                        </button>
                        <input type="hidden" name="TransactionID" value="<?php echo $row['TransactionID']; ?>">
                        <button type="submit" name="confirm" class="action-btn confirm-btn" <?php echo $row['StatusID'] == 6 ? '' : 'disabled'; ?>>
                            Confirm
                        </button>
                        <button type="submit" name="decline" class="action-btn decline-btn" <?php echo $row['StatusID'] == 6 ? '' : 'disabled'; ?>>
                            Decline
                        </button>
                    </form>
                    <?php
                    $reviewQuery = "SELECT * FROM review WHERE TransactionID = {$row['TransactionID']}";
                    $reviewResult = mysqli_query($conn, $reviewQuery);
                    if (mysqli_num_rows($reviewResult) > 0) {
                        echo "Reviewed";
                    } else {
                        ?>
                        <form action="review.php" method="post">
                            <input type="hidden" name="PhotographerID" value="<?php echo $row['PhotographerID']; ?>">
                            <input type="hidden" name="TransactionID" value="<?php echo $row['TransactionID']; ?>">
                            <button type="submit" name="review" class="action-btn review-btn">
                                Review
                            </button>
                        </form>
                        <?php
                    }
                    ?>
                    <form action="download_pdf.php" method="post">
                        <input type="hidden" name="TransactionID" value="<?php echo $row['TransactionID']; ?>">
                        <button type="submit" name="downloadPDF" class="action-btn download-pdf-btn" <?php echo in_array($row['StatusID'], [2, 3, 6]) ? '' : 'disabled'; ?>>
                            Download PDF
                        </button>
                    </form>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    </body>
    </html>
    <?php
} else {
    // Handle query error
    echo "Error: " . mysqli_error($conn);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel'])) {
    $transactionID = $_POST['TransactionID'];

    $updateTransactionQuery = "UPDATE Transactions SET StatusID = 5 WHERE TransactionID = $transactionID";
    $updateTransactionResult = mysqli_query($conn, $updateTransactionQuery);

    $updateScheduleQuery = "UPDATE availability_schedule AS s
                            JOIN availability_time AS t ON s.scheduleid = t.scheduleid
                            JOIN Transactions AS tr ON tr.Time_ID = t.time_id
                            SET s.schedule_status_id = 1
                            WHERE tr.TransactionID = $transactionID";
    $updateScheduleResult = mysqli_query($conn, $updateScheduleQuery);

    if ($updateTransactionResult && $updateScheduleResult) {
        echo '<script>alert("Cancel Successfully!");</script>';
        echo '<script> window.location.href = "appointment.php"; </script>';
    } else {
        echo '<script>alert("Error canceling the transaction.");</script>';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['formSubmitted'])) {
    $transactionID = $_POST['TransactionID'];

    if (isset($_POST['confirm'])) {
        $updateQuery = "UPDATE Transactions SET StatusID = 2 WHERE TransactionID = $transactionID";
        $updateResult = mysqli_query($conn, $updateQuery);

        if ($updateResult) {
            echo '<script>alert("Transaction confirmed!");</script>';
            echo '<script> window.location.href = "appointment.php"; </script>';
        } else {
            echo '<script>alert("Error confirming the transaction.");</script>';
        }
    }

    if (isset($_POST['decline'])) {
        $updateQuery = "UPDATE Transactions SET StatusID = 3 WHERE TransactionID = $transactionID";
        $updateResult = mysqli_query($conn, $updateQuery);

        if ($updateResult) {
            echo '<script>alert("Transaction declined.");</script>';
            echo '<script> window.location.href = "appointment.php"; </script>';
        } else {
            echo '<script>alert("Error declining the transaction.");</script>';
        }
    }
}
?>
 <?php include("../include/footer.php"); ?>