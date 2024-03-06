<?php
// Include your database connection
include("../include/config.php");

// Start the session
session_start();
include("../photographer/header.php");

// Check if photographerID is set
if (!isset($_SESSION['PhotographerID'])) {
    // Redirect to login page
    header("Location: ../admin/login.php");
    exit();
}

// Retrieve photographerID from the session
$photographerID = $_SESSION['PhotographerID'];

// Fetch data from the Transactions table
$query = "SELECT t.TransactionID, t.CustomerID, t.ReservationDate, t.Time_ID, t.PlaceID, t.PackageID, t.StatusID,
            t.TransactionDate, c.Name, tm.start_time, tm.end_time, p.PlaceName, pk.PackageName, pk.Price, ts.StatusName
            FROM Transactions t
            JOIN customers c ON t.CustomerID = c.CustomerID
            JOIN time tm ON t.Time_ID = tm.Time_ID
            JOIN places p ON t.PlaceID = p.PlaceID
            JOIN packages pk ON t.PackageID = pk.PackageID
            JOIN transactionstatus ts ON t.StatusID = ts.StatusID
            WHERE t.PhotographerID = $photographerID";

$result = mysqli_query($conn, $query); // Use the correct connection variable $conn

// Check for query execution success
if ($result) {
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photographer Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        /* Resetting default margin and padding */
        body, h1, h2, h3, h4, h5, h6, p, ul, ol, li, figure, figcaption, blockquote, dl, dd, dt {
            margin: 0;
            padding: 0;
        }

        /* Add your custom styles for the header and navigation bars */
        .navbar {
            /* Styles for the main navigation bar */
            background-color: #213555;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo img {
            margin-left: 40px;
            height: 80px; /* Adjust as needed */
            width: auto; /* Ensures the image scales with height */
        }

        .navbar .search input[type="text"] {
            padding: 10px;
            border: none;
            border-radius: 10px;
            margin-right: 10px;
            width: 300px;
        }

        .navbar .search button {
            padding: 5px 10px;
            background-color: #4F709C;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
        }

        .navbar .profile a {
            color: #fff;
            text-decoration: none;
        }

        .sub-navbar {
            /* Styles for the secondary navigation bar */
            background-color: #4F709C;
            color: #fff;
            padding: 10px;
        }

        .sub-navbar ul {
            list-style-type: none;
            display: flex;
            justify-content: space-around;
        }

        .sub-navbar ul li {
            margin-right: 10px;
        }

        .sub-navbar ul li a {
            color: #fff;
            text-decoration: none;
        }

        /* Container for cards */
        .container {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                margin-top: 50px;
                max-width: 100%;
                margin-left: 50px;
            }

            .card {
                background-color: #ffffff;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                padding: 20px;
                width: 300px;
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
   
    </div>
    <div class="container">
        <!-- Your PHP loop to generate cards here -->
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="card">
                <div class="card-header"><?php echo $row['TransactionID']; ?></div>
                <div class="card-content">
                    <span><strong>Customer:</strong> <?php echo $row['Name']; ?></span>
                    <span><strong>Reservation Date:</strong> <?php echo $row['ReservationDate']; ?></span>
                    <span><strong>Time:</strong> <?php echo $row['start_time'] . ' - ' . $row['end_time']; ?></span>
                    <span><strong>Transaction Date:</strong> <?php echo $row['TransactionDate']; ?></span>
                    <span><strong>Place:</strong> <?php echo $row['PlaceName']; ?></span>
                    <span><strong>Package Name:</strong> <?php echo $row['PackageName']; ?></span>
                    <span><strong>Price:</strong> <?php echo $row['Price']; ?></span>
                    <span><strong>Status:</strong> <?php echo $row['StatusName']; ?></span>
                </div>
                <div class="card-actions">
                    <button class="action-btn confirm-btn">Confirm</button>
                    <button class="action-btn decline-btn">Decline</button>
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

// Function to get customer name based on customer ID
function getCustomerName($conn, $customerID) {
    $query = "SELECT CustomerName FROM customers WHERE CustomerID = $customerID";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['CustomerName'];
    }

    return "N/A"; // Return a default value if customer name is not found
}
?>
