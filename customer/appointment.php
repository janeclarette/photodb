<?php
// Include necessary files and establish a database connection
include("../include/config.php");
// Start the session
session_start();
include("../customer/header.php");
// Check if customerID is set in the session
if (!isset($_SESSION['CustomerID'])) {
    // Redirect to login page or handle the case when the customer is not logged in
    header("Location: ../customer/login.php");
    exit();
}

// Retrieve customerID from the session
$customerID = $_SESSION['CustomerID'];

// Fetch data from the Transactions table based on customerID
$query = "SELECT t.TransactionID, t.PhotographerID, t.ReservationDate, t.Time_ID, t.PlaceID, t.PackageID, t.StatusID,
            t.TransactionDate, pt.Name, tm.start_time, tm.end_time, p.PlaceName, pk.PackageName, pk.Price, ts.StatusName
            FROM Transactions t
            JOIN photographers pt ON t.PhotographerID = pt.PhotographerID
            JOIN time tm ON t.Time_ID = tm.Time_ID
            JOIN places p ON t.PlaceID = p.PlaceID
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

            .download-pdf-btn {
                padding: 6px 12px;
                border-radius: 4px;
                border: none;
                cursor: pointer;
                background-color: #4F709C;
                color: white;
                font-size: 14px;
            }

            .download-pdf-btn:disabled {
                background-color: #cccccc;
                cursor: not-allowed;
            }
            .cancel-btn {
    padding: 6px 12px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    color: white;
    font-size: 14px;
    background-color: #f44336; /* Button color */
}

.cancel-btn:hover {
    opacity: 0.8;
}

.cancel-btn:disabled {
    background-color: #cccccc; /* Disabled button color */
    cursor: not-allowed;
}
        </style>
    </head>
    <body>
    <table border="1">
        <tr>
            <th>Transaction ID</th>
            <!-- Add more headers as needed -->
            <th>Photographer</th>
            <th>Reservation Date</th>
            <th>Time</th>
            <th>Transaction Date</th>
            <th>Place</th>
            <th>Package Name</th>
            <th>Price</th>
            <th>Status</th>
            <th>Payment Action</th>
            <th>Service Action</th>
            <th>Review</th>
            <th>Receipt</th> <!-- New column for Receipt -->
        </tr>

        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?php echo $row['TransactionID']; ?></td>
                <td><?php echo $row['Name']; ?></td>
                <td><?php echo $row['ReservationDate']; ?></td>
                <td><?php echo $row['start_time'] . ' - ' . $row['end_time']; ?></td>
                <td><?php echo $row['TransactionDate']; ?></td>
                <td><?php echo $row['PlaceName']; ?></td>
                <td><?php echo $row['PackageName']; ?></td>
                <td><?php echo $row['Price']; ?></td>
                <td><?php echo $row['StatusName']; ?></td>
                <td>
                    <!-- Add your actions or buttons here -->
                    <form action="payment.php" method="post">
        <input type="hidden" name="TransactionID" value="<?php echo $row['TransactionID']; ?>">
        <!-- Other form fields and buttons go here -->
        <button type="submit" name="payment" class="payment-btn"
            <?php echo $row['StatusID'] == 4 ? '' : 'disabled'; ?>>
            Payment
        </button>
    </form>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="cancelForm<?php echo $row['TransactionID']; ?>">
        <input type="hidden" name="TransactionID" value="<?php echo $row['TransactionID']; ?>">
        <!-- Other form fields and buttons go here -->
        <button type="submit" name="cancel" class="cancel-btn" onclick="return confirm('Are you sure you want to cancel this transaction?');"
            <?php echo $row['StatusID'] == 4 ? '' : 'disabled'; ?>>
            Cancel
        </button>
    </form>
                </td>
                <td>
                    <!-- Add your other actions or buttons here -->
                    <?php if ($row['StatusID'] == 6): ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="TransactionID"
                                value="<?php echo $row['TransactionID']; ?>">
                            <button type="submit" name="confirm" class="confirm-btn"
                                <?php echo $row['StatusID'] == 6 ? '' : 'disabled'; ?>>
                                Confirm
                            </button>
                            <button type="submit" name="decline" class="decline-btn"
                                <?php echo $row['StatusID'] == 6 ? '' : 'disabled'; ?>>
                                Decline
                            </button>
                            <input type="hidden" name="formSubmitted" value="1">
                        </form>
                    <?php endif; ?>
                </td>
                <td>
                    <?php
                    // Check if the transaction has been reviewed
                    $reviewQuery = "SELECT * FROM review WHERE TransactionID = {$row['TransactionID']}";
                    $reviewResult = mysqli_query($conn, $reviewQuery);
                    if (mysqli_num_rows($reviewResult) > 0) {
                        // Transaction has been reviewed
                        echo "Reviewed";
                    } else {
                        // Transaction has not been reviewed
                        ?>
                        <form action="review.php" method="post">
                            <input type="hidden" name="PhotographerID"
                                value="<?php echo $row['PhotographerID']; ?>">
                            <input type="hidden" name="TransactionID"
                                value="<?php echo $row['TransactionID']; ?>">
                            <button type="submit" name="review" class="review-btn">
                                Review
                            </button>
                        </form>
                        <?php
                    }
                    ?>
                </td>
                <td>
                    <!-- Add the Receipt column content -->
                    <form action="download_pdf.php" method="post" id="downloadPdfForm<?php echo $row['TransactionID']; ?>">
                        <input type="hidden" name="TransactionID" value="<?php echo $row['TransactionID']; ?>">
                        <button type="submit" name="downloadPDF" class="download-pdf-btn"
                            <?php echo in_array($row['StatusID'], [2, 3, 6]) ? '' : 'disabled'; ?>>
                            Download PDF
                        </button>
                    </form>
                </td>
            </tr>
            <?php
        }
        ?>

    </table>
    <?php
    // Handling form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel'])) {
        $transactionID = $_POST['TransactionID'];
    
        // Update the transaction status to 5 (cancel)
        $updateTransactionQuery = "UPDATE Transactions SET StatusID = 5 WHERE TransactionID = $transactionID";
        $updateTransactionResult = mysqli_query($conn, $updateTransactionQuery);
    
        // Update the schedule status back to 1 (available)
        $updateScheduleQuery = "UPDATE availability_schedule AS s
                                JOIN availability_time AS t ON s.scheduleid = t.scheduleid
                                JOIN Transactions AS tr ON tr.Time_ID = t.time_id
                                SET s.schedule_status_id = 1
                                WHERE tr.TransactionID = $transactionID";
        $updateScheduleResult = mysqli_query($conn, $updateScheduleQuery);
    
        if ($updateTransactionResult && $updateScheduleResult) {
            echo '<script>alert("Cancel Successfully!");</script>';
            // Refresh the page
            echo '<script> window.location.href = "appointment.php"; </script>';
        } else {
            echo '<script>alert("Error canceling the transaction.");</script>';
        }
    }
    ?>    </body>
    </html>
    <?php
} else {
    // Handle query error
    echo "Error: " . mysqli_error($conn);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['formSubmitted'])) {
    $transactionID = $_POST['TransactionID'];

    if (isset($_POST['confirm'])) {
        // Update the status to confirmed (statusID = 2)
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
        // Update the status to declined (statusID = 3)
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
