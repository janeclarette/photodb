


<?php
session_start();
include("../include/config.php");
//include("../include/header.php");
include("../customer/header.php"); 

// Check if the customer is logged in
if (!isset($_SESSION['CustomerID'])) {
    $_SESSION['message'] = "You must be logged in to view your profile. <a href='../admin/login.php'>Click here to log in</a>";
    header("Location: /lib2/customer/customerdashboard.php");
    exit();
}


// Get the customer ID from the session
$customerID = $_SESSION['CustomerID'];

// Fetch customer information including city details
$sql = "SELECT c.Name, c.Phone_Number, c.Gcash_Number,c.Address, ct.CityName AS City, c.Email, c.img_customer 
        FROM customers c
        LEFT JOIN cities ct ON c.CityID = ct.CityID
        WHERE c.CustomerID = $customerID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output customer profile
    $row = $result->fetch_assoc();
?>
<h2> Customer Profile </h2>
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
                <span class="label">Gcash Number:</span>
                <span class="value"><?php echo $row['Gcash_Number']; ?></span>
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
    <a href="update_profile.php" class="edit-profile">Edit Profile</a>
</div>

<?php
} else {
    echo "No results found.";
}
?>


<style>
        h2 {
            margin-top: 30px;
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
            font-family: serif;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            margin-bottom: 30px;
            height: 650px; /* Allow height to adjust based on content */
            background-color: #ffffff;
            padding: 20px; /* Add padding for spacing */
            border-radius: 10px; /* Add some border radius for a rounded look */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add a subtle shadow for depth */
        }

        .profile {
            display: flex;
            flex-wrap: wrap; /* Allow flex items to wrap */
            margin-left: 50px;
            margin-top: 20px;
        }

        .profile-image {
            margin-bottom: 200px;
            height: 250px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 20px;
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-details {
            flex: 1; /* Take remaining space */
            display: flex;
            flex-direction: column;
            margin-left: 30px;
        }

        .profile-details .detail {
            margin-bottom: 20px;
            margin-top: 20px;
            margin-left: 40px;
            display: block;
        }

        .profile-details .label {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
        }

        .profile-details .value {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            width: 200px;
            display: block;
            margin-bottom: 10px;
        }

        .edit-profile {
            padding: 8px 16px;
            background-color: #4F709C;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 400px; /* Align to the right */
            margin-top: 100px; /* Add some top margin */
        }

        .edit-profile:hover {
            background-color: #375d83;
        }


</style>


