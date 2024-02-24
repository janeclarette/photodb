<?php include("../include/config.php"); 
//header for general lang ?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>General Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="../include/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
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
        <div class="profile"><i class="fa-regular fa-user"></i>

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
            <li><a href="/photodb/customer/customerdashboard.php">Home</a></li>       
            <li><a href="/photodb/customer/photographer.php">Photographers</a></li>


            <li class="dropdown">
            <a href="#">Services</a>
            <div class="dropdown-content">
            <?php
        $serviceTypesSql = "SELECT * FROM servicetypes";
        $serviceTypesResult = $conn->query($serviceTypesSql);

        while ($serviceTypeRow = $serviceTypesResult->fetch_assoc()) {
            $typeName = $serviceTypeRow['TypeName'];
            $typeParam = urlencode(strtolower(str_replace(
                array('Wedding Photography', 'Portrait Photography', 'Event Coverage', 'Commercial Photography', 'Family Photography', 'Fashion Photography', 'Newborn Photography', 'Landscape Photography', 'Food Photography', 'Sports Photography'),
                array('wedding', 'portrait', 'event', 'commercial', 'family', 'fashion', 'newborn', 'landscape', 'food', 'sports'),
                $typeName
            )));

            echo "<a href='$typeParam.php'>$typeName</a>";
        }
        ?>
            </div>
            </li>
            <li><a href="/photodb/customer/review.php">Reviews</a></li>
            <li><a href="/photodb/customer/gallery.php">Photo Gallery</a></li>
            <li><a href="/photodb/customer/price.php">Pricing</a></li>
            <li><a href="/photodb/admin/aboutus.php">About Us</a></li>
            <li><a href="/photodb/admin/contactus.php">Contact Us</a></li>
        </ul>
    </nav>

</body>
</html>

<?php 
session_start();
include("../include/config.php");

// Check if the customer is logged in
if (!isset($_SESSION['CustomerID'])) {
    $_SESSION['message'] = "You must be logged in to view your profile. <a href='../admin/login.php'>Click here to log in</a>";
    header("Location: /lib2/customer/customerdashboard.php");
    exit();
}

// Get the customer ID from the session
$customerID = $_SESSION['CustomerID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission for updating profile

    // Retrieve updated profile information
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $city = $_POST['city'];

    // Update the database with new information
    $update_customer_sql = "UPDATE customers SET Name='$name', Email='$email', Phone_Number='$phone_number', Address='$address' WHERE CustomerID=$customerID";
    if ($conn->query($update_customer_sql) === TRUE) {
        // Update the cities table with the given city ID
        $update_city_sql = "UPDATE cities SET CityName='$city' WHERE CityID=(SELECT CityID FROM customers WHERE CustomerID=$customerID)";
        if ($conn->query($update_city_sql) === TRUE) {
            // Display JavaScript alert
            echo '<script>';
            echo 'alert("Profile edited successfully");';
            echo 'window.location.href = "profile.php";';
            echo '</script>';
            exit(); // Exit to prevent further execution of PHP code
        } else {
            echo "Error updating city record: " . $conn->error;
        }
    } else {
        echo "Error updating customer record: " . $conn->error;
    }
}

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
<!-- Display profile information -->
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
            <form method="post">
                <div class="detail">
                    <span class="label">Name:</span>
                    <input type="text" name="name" value="<?php echo $row['Name']; ?>">            
                </div>
                <div class="detail">
                    <span class="label">Email:</span>
                    <input type="email" name="email" value="<?php echo $row['Email']; ?>">
                </div>
                <div class="detail">
                    <span class="label">Phone Number:</span>
                    <input type="text" name="phone_number" value="<?php echo $row['Phone_Number']; ?>">
                </div>
                <div class="detail">
                    <span class="label">Address:</span>
                    <input type="text" name="address" value="<?php echo $row['Address']; ?>">
                </div>
                <div class="detail">
                    <span class="label">City:</span>
                    <input type="text" name="city" value="<?php echo $row['City']; ?>">
                </div>
                <button type="submit">Update Profile</button>
            </form>
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
        button[type="submit"] {
            padding: 8px 16px;
            background-color: #4F709C;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px; /* Adjust top margin as needed */
        }

        button[type="submit"]:hover {
            background-color: #375d83;
        }

</style>




