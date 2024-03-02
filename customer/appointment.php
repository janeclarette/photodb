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
$query = "SELECT * FROM Transactions WHERE CustomerID = $customerID";

$result = mysqli_query($conn, $query);

// Check for query execution success
if ($result) {
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

    body {
        background-color: #E0F4FF;
    }
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
        .logout {
            margin-right: 40px; /* Adjust the margin between the items */
        }

        .sign-in .dropdown,
        .logout a {
            padding: 25px; /* Adjust the padding for better spacing */
        }

        .message{
            margin-right: 10px; /* Adjust the margin between the items */
        }

        /* Welcome section */
        .welcome {
            padding: 40px;
            margin-bottom: 20px;
            text-align: center;
            background-image: url('../uploads/cover.jpg');  /* Set the path to your cover image */
            background-size: cover;
            background-position: center bottom; /* Lower the background image */
            height: 500px; /* Adjust the height as needed */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome h2 {
            text-align: center;
            font-size: 6rem;
            font-family: 'Satisfy';
            color: #fff;
        }

        /* Services section */
        .services {
            padding: 50px;
            margin-bottom: 20px;
            text-align: center;
        }

        .services h2 {
            text-align: center;
            font-size: 3rem;
            font-family: 'Satisfy';
            color: #333;
        }
        .services h3 {
            text-align: center;
            font-size: 2rem;
            font-family: 'Satisfy';
            color: #333;
        }
        .services h6 {
            text-align: center;
            font-size: 1.5rem;
            font-family: 'Cinzel', serif;
            color: #333;
            margin: 20px;
        }
        .services p {
            text-align: center;
            font-size: 1.5rem;
            font-family: 'Cinzel', serif;
            color: #333;
        }
        .service-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Centers the items horizontally */
        }

        .service {
            width: 200px; /* Adjust the width of each service */
            margin: 40px; /* Adjust the spacing between services */
            text-align: center;
        }

        .service img {
            width: 150px; /* Adjust the width of the service icons */
            height: auto;
            margin: 20px;
        }

        /* Featured events section */
        .featured-events {
            padding: 50px;
            margin-bottom: 20px;
            text-align: center;
        }
        .featured-events h2 {
            text-align: center;
            font-size: 3rem;
            font-family: 'Satisfy';
            color: #333;
        }
        .featured-events p {
            text-align: center;
            font-size: 1.5rem;
            font-family: 'Cinzel', serif;
            color: #333;
        }
    .payment-btn {
        .payment-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer; /* Always set the cursor to pointer */
    background-color: #4F709C; /* Force the background color to be blue */
    color: #fff;
    margin-top: 10px;
}

    }
    </style>
       <table border="1">
            <tr>
                <th>Transaction ID</th>
                <!-- Add more headers as needed -->
                <th>Photographer ID</th>
                <th>Reservation Date</th>
                <th>Time</th>
                <th>Transaction Date</th>
                <th>Place</th>
                <th>Customer Place</th>
                <th>Package Name</th>
                <th>Status</th>
                <th>Payment Action</th>
                <th>Service Action</th>
            </tr>

            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <!-- Display data for each transaction -->
                    <td><?php echo $row['TransactionID']; ?></td>
                    <td><?php echo $row['PhotographerID']; ?></td>
                    <!-- Fetch additional information from other tables based on your database structure -->
                    <td><?php echo $row['ReservationDate']; ?></td>
                    <td><?php echo $row['Time_ID']; ?></td>
                    <td><?php echo $row['TransactionDate']; ?></td>
                    <td><?php echo $row['PlaceID']; ?></td>
                    <td><?php echo $row['CustomerPlaceID']; ?></td>
                    <td><?php echo $row['PackageID']; ?></td>
                    <td><?php echo $row['StatusID']; ?></td>
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
                        </form>
                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['payment'])) {
                            // Handle payment action or redirection
                            header("Location: payment.php?TransactionID=" . $row['TransactionID']);
                            exit();
                        }
                        ?>
                    </td>
                 <td>
<!-- Add your other actions or buttons here -->
<?php if ($row['StatusID'] == 6): ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="TransactionID" value="<?php echo $row['TransactionID']; ?>">
        <button type="submit" name="confirm" class="confirm-btn" <?php echo $row['StatusID'] == 6 ? '' : 'disabled'; ?>>
            Confirm
        </button>
        <button type="submit" name="decline" class="decline-btn" <?php echo $row['StatusID'] == 6 ? '' : 'disabled'; ?>>
            Decline
        </button>
        <input type="hidden" name="formSubmitted" value="1">
    </form>
<?php endif; ?>
</td>
</tr>
<?php
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['formSubmitted'])) {
    if (isset($_POST['confirm']) || isset($_POST['decline'])) {
        $transactionID = $_POST['TransactionID'];
        $newStatus = isset($_POST['confirm']) ? 2 : 3;

        // Update the StatusID in the Transactions table
        $updateQuery = "UPDATE Transactions SET StatusID = $newStatus WHERE TransactionID = $transactionID";
        $updateResult = mysqli_query($conn, $updateQuery);

        if ($updateResult) {
            // Display a JavaScript notification
            echo '<script>alert("Status updated successfully!");</script>';
            echo '<script>window.location.href = "appointment.php";</script>';

        } else {
            // Handle update query error
            echo "Error updating status: " . mysqli_error($conn);
        }
    }
}
?>


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