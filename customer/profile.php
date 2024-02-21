<?php
session_start();
include("../include/config.php");
include("../include/header.php");

// Check if the customer is logged in
if (!isset($_SESSION['CustomerID'])) {
    $_SESSION['message'] = "You must be logged in to view your profile. <a href='../admin/login.php'>Click here to log in</a>";
    header("Location: /lib2/customer/customerdashboard.php");
    exit();
}

// Get the customer ID from the session
$customerID = $_SESSION['CustomerID'];

// Fetch customer information including city details
$sql = "SELECT c.Name, c.Phone_Number, c.Address, ct.CityName AS City, c.Email, c.img_customer 
        FROM customers c
        LEFT JOIN cities ct ON c.CityID = ct.CityID
        WHERE c.CustomerID = $customerID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output customer profile
    $row = $result->fetch_assoc();
?>
<div class="container">
    <div class="profile">
        <div class="profile-image">
            <?php
            if ($row['img_customer']) {
                echo "<img src='{$row['img_customer']}' alt='Profile Image'>";
            } else {
                echo "<p>No image available</p>";
            }
            ?>
        </div>
        <a href="#" class="edit-profile">Edit Profile</a>
        <div class="profile-details">
            <div class="detail">
                <span class="label">Name:</span>
                <span class="value"><?php echo $row['Name']; ?></span>
            </div>
            <div class="detail">
                <span class="label">Email:</span>
                <span class="value"><?php echo $row['Email']; ?></span>
            </div>
            <div class="detail">
                <span class="label">Phone Number:</span>
                <span class="value"><?php echo $row['Phone_Number']; ?></span>
            </div>
            <div class="detail">
                <span class="label">Address:</span>
                <span class="value"><?php echo $row['Address']; ?></span>
            </div>
            <div class="detail">
                <span class="label">City:</span>
                <span class="value"><?php echo $row['City']; ?></span>
            </div>
        </div>
    </div>
</div>



<?php
} else {
    echo "No results found.";
}
?>


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

}.container {
    max-width: 800px;
    margin: 50px auto;
        background-color: #ffffff;
        height: 50vh;
}

.profile-container {
    display: flex;
    align-items: center;
    background-color: #ffffff;
    border-radius: 20px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.profile-image {
    flex-shrink: 0;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 20px;
    margin-left: 30px;
}

.profile-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-details {
    flex: 1;
    padding: 20px;
}

.detail {
    margin-bottom: 10px;
}

.label {
    font-weight: bold;
}

.edit-profile {
    margin-top: 300px;
    padding: 8px 16px;
    background-color: #4F709C;
    color: #ffffff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.edit-profile:hover {
    background-color: #375d83;
}


</style>