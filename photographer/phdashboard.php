<?php
// Include your database connection
include("../include/config.php");

// Start the session
session_start();

// Check if photographerID is set
if (!isset($_SESSION['PhotographerID'])) {
    // Redirect to login page
    header("Location: ../admin/login.php");
    exit();
}

// Retrieve photographerID from the session
$photographerID = $_SESSION['PhotographerID'];

// Fetch data from the Transactions table
$query = "SELECT * FROM Transactions WHERE PhotographerID = $photographerID";

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
        </style>
    </head>
    <body>
        <!-- Main header with navigation bar -->
        <header class="navbar">
            <div class="logo">
                <!-- Logo (upper left corner) -->
                <a href="#"><img src="../uploads/C.png" alt="Logo"></a>
            </div>
            <div class="search">
                <!-- Search (center) -->
                <input type="text" placeholder="Search">
                <button type="submit">Search</button>
            </div>
            <div class="profile">
                <!-- Profile (upper right corner) -->
                <div class="sign-in">
                    <a href="phprofile.php?photographerID=<?php echo $photographerID; ?>"><i class="fa-regular fa-user"></i></a>
                </div>
                <div class="message">
                    <!-- Logout link -->
                    <a href="/photodb/photographer/message.php"><i class="fa-regular fa-message"></i></a>
                </div>
                <div class="logout">
                    <!-- Logout link -->
                    <a href="/photodb/admin/logout.php"><i class="fas fa-sign-in-alt"></i></a>
                </div>
            </div>
        </header>

        <!-- Secondary navigation bar -->
        <nav class="sub-navbar">
            <ul>
                <!-- Navigation links -->
                <li><a href="phdashboard.php">Home</a></li>
                <li><a href="work_create.php">Portfolio</a></li>
                <li><a href="schedule.php">Schedule</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="package.php">Package</a></li>
                <li><a href="place.php">Place</a></li>
                <li><a href="#">Reviews</a></li>
            </ul>
        </nav>

        <!-- Table to display transactions -->
        <table border="1">
            <tr>
                <th>Transaction ID</th>
                <th>Customer ID</th>
                <th>Reservation Date</th>
                <th>Time</th>
                <th>Transaction Date</th>
                <th>Place</th>
                <th>Customer Place</th>
                <th>Package Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?php echo $row['TransactionID']; ?></td>
                    <td><?php echo $row['CustomerID']; ?></td>
                    <!-- Fetch additional information from other tables based on your database structure -->
                    <!-- For example, assuming there is a 'customers' table -->
                    <td><?php echo $row['ReservationDate']; ?></td>
                    <td><?php echo $row['Time_ID']; ?></td>
                    <td><?php echo $row['TransactionDate']; ?></td>
                    <td><?php echo $row['PlaceID']; ?></td>
                    <td><?php echo $row['CustomerPlaceID']; ?></td>
                    <td><?php echo $row['PackageID']; ?></td>
                    <td><?php echo $row['StatusID']; ?></td>
                    <td>
                        <!-- Add your actions or buttons here -->
                        <form action="" method="post">
                            <input type="hidden" name="transaction_id" value="<?php echo $row['TransactionID']; ?>">
                            <button type="submit" name="accept">Accept</button>
                            <button type="submit" name="decline">Decline</button>
                        </form>
                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $transactionID = $_POST['transaction_id'];
                            $action = isset($_POST['accept']) ? 'accept' : (isset($_POST['decline']) ? 'decline' : '');
                            
                            if ($action === 'accept') {
                                // Handle accept action, update the status to 4
                                $updateQuery = "UPDATE Transactions SET StatusID = 4 WHERE TransactionID = $transactionID";
                                mysqli_query($conn, $updateQuery);
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
