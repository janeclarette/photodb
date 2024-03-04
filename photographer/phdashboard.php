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
        <!-- Add your CSS stylesheets here -->
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
            /* Dropdown menu */
            .dropdown-content {
                display: none;
                position: absolute;
                background-color: #9BABB8;
                min-width: 160px;
                z-index: 1=;
            }

            .dropdown-content a {
                color: #fff;
                padding: 12px 16px;
                text-decoration: none;
                display: block;
            }

            .dropdown:hover .dropdown-content {
                display: block;
            }
            /* Container for sections */
            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 20px;
            }
            .profile {
                display: flex;
                align-items: center;
            }

            .sign-in,
            .logout{
                margin-right: 40px; /* Adjust the margin between the items */
            }

            .sign-in .dropdown,
            .logout a {
                padding: 25px; /* Adjust the padding for better spacing */
            }

            .message{
                margin-right: 10px; /* Adjust the margin between the items */
            }

            /* Add your table styles here */
            table {
                border-collapse: collapse;
                width: 100%;
                margin-top: 20px;
            }

            table, th, td {
                border: 1px solid #ddd;
            }

            th, td {
                padding: 10px;
                text-align: left;
            }
            /* Table styles */
table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 20px;
}

table th,
table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

/* Header row */
table th {
    background-color: #213555;
    color: #fff;
}

/* Alternate row colors */
table tr:nth-child(even) {
    background-color: #f2f2f2;
}

/* Hover effect on rows */
table tr:hover {
    background-color: #ddd;
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
    margin-left: 5px;
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
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $transactionID = $_POST['transaction_id'];
                            $action = isset($_POST['accept']) ? 'accept' : (isset($_POST['decline']) ? 'decline' : '');
                            
                            if ($action === 'accept') {
                                // Handle accept action, update the status to 4
                                $updateQuery = "UPDATE Transactions SET StatusID = 4 WHERE TransactionID = $transactionID";
                                mysqli_query($conn, $updateQuery);
                                // Add JavaScript alert and redirect
                                echo '<script>';
                                echo 'alert("Accepted successfully");';
                                echo 'window.location.href = "phdashboard.php";';
                                echo '</script>';
                            } elseif ($action === 'decline') {
                                // Handle decline action, update the status to 5
                                $updateQuery = "UPDATE Transactions SET StatusID = 5 WHERE TransactionID = $transactionID";
                                mysqli_query($conn, $updateQuery);
                            }
                            
                            // // Refresh the page after processing the form
                            // header("Location: " . $_SERVER['PHP_SELF']);
                            // exit();
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
