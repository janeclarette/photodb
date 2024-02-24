<?php
session_start();
include("../include/config.php");
include("../admin/adminheader.php");



// Insert new customer
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_customer'])) {
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $city_id = $_POST['city_id'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Image upload code here if necessary
    
    $insert_query = "INSERT INTO customers (Name, Phone_Number, Address, CityID, Email, Username, Password) 
                     VALUES ('$name', '$phone_number', '$address', '$city_id', '$email', '$username', '$password')";
    if (mysqli_query($conn, $insert_query)) {
        $_SESSION['success_message'] = "Customer added successfully.";
    } else {
        $_SESSION['error_message'] = "Error: " . mysqli_error($conn);
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM customers WHERE CustomerID = $delete_id";
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['success_message'] = "Customer deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Error: " . mysqli_error($conn);
    }
}

// Fetch customers
$customers_query = "SELECT * FROM customers";
$customers_result = mysqli_query($conn, $customers_query);

// Check for query execution errors
if (!$customers_result) {
    die("Customer query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
</head>
<body>
    <div class='cover-section'><h2>Customers</h2></div>

    <?php
    // Display success or error message
    if (isset($_SESSION['success_message'])) {
        echo "<div class='success'>{$_SESSION['success_message']}</div>";
        unset($_SESSION['success_message']);
    } elseif (isset($_SESSION['error_message'])) {
        echo "<div class='error'>{$_SESSION['error_message']}</div>";
        unset($_SESSION['error_message']);
    }
    ?>

    <?php
    // Loop through the customer data and display each in a separate div
    while ($customer = mysqli_fetch_assoc($customers_result)) {
    ?>
        <div class='book-card'>
            <?php
            // Display the customer image if available
            if ($customer['img_customer']) {
                echo "<img src='../uploads/{$customer['img_customer']}' alt='Customer Image' class='customer-image'>";
            } else {
                echo "<p>No image available</p>";
            }
            ?>
            <p style="font-family: 'Satisfy'; font-size:1.5rem;"><strong><?php echo $customer['Name']; ?></strong></p>
            <p>Address: <?php echo $customer['Address']; ?></p>
            <p>Contact Number: <?php echo $customer['Phone_Number']; ?></p>
            <p>Email: <?php echo $customer['Email']; ?></p>
            <!-- Additional fields display if needed -->
            <p class='actions'>
                <a href='cdelete.php?delete_id=<?php echo $customer['CustomerID']; ?>' class='delete-link'>Delete Customer</a>
            </p>
        </div>
    <?php
    }
    ?>

</body>
</html>

<style>
    body {
        background-image: url('../uploads/b.jpg');
        background-size: cover;
        background-attachment: fixed;
        height: 100vh;
    }

    h2 {
        margin-top: 20px;
        text-align: center;
        color: #fff;
        font-weight: bold;
        font-size: 6rem;
        font-family: 'Satisfy';
    }

    .cover-section {
        margin-top: 20px;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .book-card {
        background-color: #E0F4FF;
        text-align: center; /* Center the book grid */
        margin-left: 50px;
        margin-right: 50px;
        margin-top: 50px;
        padding: 50px;
        border-radius: 20px;
        display: inline-block;
        height: 370px;
    }

    .customer-image {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        margin-bottom: 15px;
    }

    .book-card p {
        color: #333;
        margin-bottom: 15px;
    }

    .actions {
        background-color: #4F709C;
        padding: 10px 10px;
        text-decoration: none;
        border-radius: 5px;
    }

    .actions a {
        color: #fff;
        text-decoration: none;
        margin-right: 10px;
    }

    .actions a:hover {
        text-decoration: none;
    }
</style>
