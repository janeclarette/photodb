<?php
session_start();
// Include your database connection
include("../include/config.php");

// Start the session

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
    <section class="title">
            <h2>Dashboard</h2>
        </section>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Photographer Page</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
        <!-- Add your CSS stylesheets here -->
        <style>
     /* CSS styles */
        /* Add your CSS stylesheets here */
        body, h1, h2, h3, h4, h5, h6, p, ul, ol, li, figure, figcaption, blockquote, dl, dd, dt {
            margin: 0;
            padding: 0;
        }
        body {
            background-image: url('../uploads/cover.jpg');
            background-size: cover;
            background-attachment: fixed;
            height: 100vh;
        }
        .background {
            margin-top: auto 0;
            text-align: center;
            color: #333;
            font-weight: bold;
            font-size: 6rem;
            font-family: 'Satisfy';
        }
        table {
            width: 90%; /* Set the width of the table */
<<<<<<< Updated upstream
            max-width: 1700px; /* Set a maximum width for the table */
            margin: 20px auto; /* Center the table horizontally */
=======
            max-width: 1200px; /* Set a maximum width for the table */
            margin: 10px auto; /* Center the table horizontally */
>>>>>>> Stashed changes
            backdrop-filter: blur(40px); 
            font-weight: bold;
            margin-bottom: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #ddd;
        }

        th {
            background-color: rgba(75, 192, 192, 10);
            color: #333;
            font-weight: bold;
        }

        tr:hover {
            background-color: #fffff0;
        }

        /* Button styles */
        .payment-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            background-color: rgba(75, 192, 192, 20);
            color: white;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.15);
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
        .title {
        padding: 40px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 20px;
    }

    .title h2 {
        text-align: center;
        font-size: 6rem;
        font-family: 'Satisfy';
        color: #333;
    }
        
/* Button styles */
table button {
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    background-color: #4F709C;
    color: #fff;
}

/* Disable style for disabled buttons */
table button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

/* Adjust button margin */
table button + button {
    margin-left: 0px;
 
}
        </style>


        <!-- Table to display transactions -->
        <table border="1">
            <tr>
                <th>Transaction ID</th>
                <th>Customer</th>
                <th>Reservation Date</th>
                <th>Time</th>
                <th>Transaction Date</th>
                <th>Place</th>
                <th>Package Name</th>
                <th>Price</th>
                <th>Status</th>
                <th>Action</th>
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
                        <form action="" method="post">
    <input type="hidden" name="transaction_id" value="<?php echo $row['TransactionID']; ?>">
    <button type="submit" name="accept" <?php echo ($row['StatusID'] == 1) ? '' : 'disabled'; ?>>Accept</button>
    <button type="submit" name="decline" <?php echo ($row['StatusID'] == 1) ? '' : 'disabled'; ?>>Decline</button>
</form>

                        <?php
                   // Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transactionID = $_POST['transaction_id'];
    $action = isset($_POST['accept']) ? 'accept' : (isset($_POST['decline']) ? 'decline' : '');

    if ($action === 'accept') {
        // Handle accept action, update the status to 4
        // $updateQuery = "UPDATE Transactions SET StatusID = 4 WHERE TransactionID = $transactionID";
        // mysqli_query($conn, $updateQuery);

// Update the schedule status to 2 (booked) based on transaction ID and date
$updateScheduleQuery = "UPDATE availability_schedule SET schedule_status_id = 2 WHERE scheduleid = ?";
$stmt = mysqli_prepare($conn, $updateScheduleQuery);
mysqli_stmt_bind_param($stmt, "i", $scheduleID);
mysqli_stmt_execute($stmt);

        // Add JavaScript alert and redirect
        echo '<script>';
        echo 'alert("Acceoted");';
        echo 'window.location.href = "phdashboard.php";';
        echo '</script>';
    } elseif ($action === 'decline') {
        // Handle decline action, update the status to 5
        $updateQuery = "UPDATE Transactions SET StatusID = 5 WHERE TransactionID = $transactionID";
        mysqli_query($conn, $updateQuery);

        // Add JavaScript alert and redirect
        echo '<script>';
        echo 'alert("Declined");';
        echo 'window.location.href = "phdashboard.php";';
        echo '</script>';
    }
}


                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>

        <!-- Add your additional HTML content here -->

        <!-- Add your JavaScript scripts here -->

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
 