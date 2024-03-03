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
        </tr>

        <?php
        // Initialize an array to track reviewed photographers
        $reviewedPhotographers = [];
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
                    // Check if the photographer has not been reviewed yet
                    if (!in_array($row['PhotographerID'], $reviewedPhotographers)) {
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
                        // Mark photographer as reviewed
                        $reviewedPhotographers[] = $row['PhotographerID'];
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>

    </table>

    <!-- Add any other HTML content or closing tags as needed -->
    </body>
    </html>
    <?php
} else {
    // Handle query error
    echo "Error: " . mysqli_error($conn);
}

// Function to get customer name based on customer ID
function getCustomerName($conn, $customerID)
{
    $query = "SELECT CustomerName FROM customers WHERE CustomerID = $customerID";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['CustomerName'];
    }

    return "N/A"; // Return a default value if customer name is not found
}
?>



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