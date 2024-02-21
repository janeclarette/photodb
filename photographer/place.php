<?php
session_start();

include("../include/config.php");

if (!isset($_SESSION['PhotographerID'])) {
    header("Location: /photodb/login.php");
    exit();
}

$photographerID = $_SESSION['PhotographerID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placeName = mysqli_real_escape_string($conn, $_POST["placeName"]);
    $address = mysqli_real_escape_string($conn, $_POST["address"]);
    $cityID = mysqli_real_escape_string($conn, $_POST["city"]);

    $insertPlaceQuery = "INSERT INTO Places (PhotographerID, PlaceName, Address, CityID) VALUES ('$photographerID', '$placeName', '$address', '$cityID')";
    if ($conn->query($insertPlaceQuery)) {
        echo '<script>';
        echo 'alert("Place added successfully");';
        echo 'window.location.href = "place.php";';
        echo '</script>';
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

    <title>Photographer Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <body>
    <header class="navbar">
        <div class="logo">
            <a href="#"><img src="../uploads/C.png" alt="Logo"></a>
        </div>
        <div class="search">
            <input type="text" placeholder="Search">
            <button type="submit">Search</button>
        </div>
        <div class="profile">
    <div class="sign-in">
    <a href="phprofile.php?photographerID=?"><i class="fa-regular fa-user"></i></a>
    </div>
    <div class="message">
        <a href="/photodb/photographer/message.php"><i class="fa-regular fa-message"></i></a>
    </div>
    <div class="logout">
        <a href="/photodb/admin/logout.php"><i class="fas fa-sign-in-alt"></i></a>
    </div>

</div>

    
    </div>

    </header>
    <nav class="sub-navbar">
        <ul>
            <li><a href="phdashboard.php">Home</a></li>
            <li><a href="work_create.php">Portfolio</a></li>
            <li><a href="schedule.php">Schedule</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="package.php">Package</a></li>
            <li><a href="place.php">Place</a></li>
            <li><a href="#">Reviews</a></li>
        </ul>
    </nav>

  <style>
        body, h1, h2, h3, h4, h5, h6, p, ul, ol, li, figure, figcaption, blockquote, dl, dd, dt {
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #213555;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo img {
            margin-left: 40px;
            height: 80px;
            width: auto; 
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
            margin-right: 40px; 
        }

        .sign-in .dropdown,
        .logout a {
            padding: 25px; 
        }

        .message{
            margin-right: 10px; 
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px; 
        }


        label {
            display: block;
            margin-bottom: 8px;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background-color: #4F709C;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #4F709C;
            color: #fff;
        }

        tbody tr:hover {
            background-color: #f5f5f5;
        }
        </style>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Place</title>
    <link rel="stylesheet" href="your_stylesheet.css">
</head>
<body>
    <h1>Create a New Place</h1>
    <form method="post" action="">
        <label for="placeName">Place Name:</label>
        <input type="text" id="placeName" name="placeName" required>

        <label for="address">Address:</label>
        <textarea id="address" name="address" required></textarea>

        <label for="city">City:</label>
        <select id="city" name="city" required>
        <option value="" disabled selected>Select your City</option>
            <?php

            $cityQuery = "SELECT CityID, CityName FROM Cities";
            $result = $conn->query($cityQuery);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row["CityID"] . '">' . $row["CityName"] . '</option>';
                }
            }
            ?>
        </select>

        <button type="submit">Create Place</button>
    </form>
        </div>

    <div class="container">
        <h2>Places Table</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Place Name</th>
                    <th>Address</th>
                    <th>City</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $placesQuery = "SELECT PlaceName, Address, CityName FROM Places INNER JOIN Cities ON Places.CityID = Cities.CityID WHERE PhotographerID = '$photographerID'";
                $placesResult = $conn->query($placesQuery);

                if ($placesResult->num_rows > 0) {
                    while ($placeRow = $placesResult->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $placeRow["PlaceName"] . '</td>';
                        echo '<td>' . $placeRow["Address"] . '</td>';
                        echo '<td>' . $placeRow["CityName"] . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">No places added yet</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
</body>
</html>