<?php 
session_start();
include("../include/config.php");
include("../photographer/header.php");

// Check if the photographer ID is provided in the URL
if (!isset($_SESSION['PhotographerID'])) {
    // Redirect to login page if not logged in
    header("Location: /photodb/photographer/login.php");
    exit();
}

// Get the photographer ID from the session
$photographerID = $_SESSION['PhotographerID'];

// Fetch photographer information including city name by joining with cities table
$sql = "SELECT p.Name, p.Phone_Number, p.Address, c.CityName AS City, p.Email, p.img_photographer 
        FROM photographers p
        LEFT JOIN cities c ON p.CityID = c.CityID
        WHERE p.PhotographerID = $photographerID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output photographer profile
    $row = $result->fetch_assoc();
} else {
    echo "No results found.";
}
?>


<h2>Photographer Profile</h2>
    
    <div class="container">
        <div class="profile">
            <div class="profile-image">
                <?php
                if ($row['img_photographer']) {
                    echo "<img src='{$row['img_photographer']}' alt='Profile Image'>";
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
                    <span class="label">Address:</span>
                    <span class="value"><?php echo $row['Address']; ?></span>
                </div>
                <div class="detail">
                    <span class="label">City:</span>
                    <span class="value"><?php echo $row['City']; ?></span>
                </div>
                <a href="editprofile.php" class="edit-profile">Edit Profile</a>
            </div>
        </div>
    </div>

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
        margin-left: 60px;
        margin-top: 20px;
    }

    .profile-image {
        margin-top: 100px;
        margin-bottom: 200px;
        height: 300px;
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
        margin-left: 50px;
    }

    .profile-details .detail {
        margin-bottom: 20px;
        margin-top: 20px;
        margin-left: 50px;
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
        border-radius: 5px;
        width:100px;
        text-decoration: none;
        margin-left: 80px;
        }

        .edit-profile:hover {
            background-color: #375d83;
        }

    </style>

