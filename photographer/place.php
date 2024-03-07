<?php
session_start();

include("../include/config.php");
include("../photographer/header.php");

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



<body>
    <h2>Create a New Place</h2>
    <div class="container">
        <div class="left-column">
            <div class="form-container">
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

                    <button type="submit" class="button">Create Place</button>

                </form>
            </div>
        </div>
        <div class="right-column">
            <table>
                <thead>
                    <tr>
                        <th>Place Name</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $placesQuery = "SELECT PlaceID, PlaceName, Address, CityName FROM Places INNER JOIN Cities ON Places.CityID = Cities.CityID WHERE PhotographerID = '$photographerID'";
                    $placesResult = $conn->query($placesQuery);

                    if ($placesResult->num_rows > 0) {
                        while ($placeRow = $placesResult->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $placeRow["PlaceName"] . '</td>';
                            echo '<td>' . $placeRow["Address"] . '</td>';
                            echo '<td>' . $placeRow["CityName"] . '</td>';
                            echo '<td>';
                            echo '<a href="updateplace.php?id=' . $placeRow["PlaceID"] . '"><button type="button">Update</button></a>';
                            echo '<a href="deleteplace.php?id=' . $placeRow["PlaceID"] . '"><button type="button">Delete</button></a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4">No places added yet</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>



<style>
        body, h1, h2, h3, h4, h5, h6, p, ul, ol, li, figure, figcaption, blockquote, dl, dd, dt {
                margin: 0;
                padding: 0;
                font-family: 'serif';
            }

            body {
                background-image: url('../uploads/cover.jpg');  
    background-size: cover;
    background-position: center bottom;
    opacity: 0.9;  /* Adjust the opacity to make the image less visible */
        }
    h2 {
        margin-top: 30px;
        text-align: center;
        color: #333;
        font-weight: bold;
        font-size: 4rem;
        font-family: 'Satisfy';
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

    .container {
            display: flex;
            align-items: flex-start;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent white background */
    border: 5px solid rgba(255,255,255, 10);
    backdrop-filter: blur(10px); /* Apply a blur effect behind the container */
    
        }

        .left-column,
.right-column {
    width: 48%; /* Adjust as needed */
    padding: 20px; /* Add padding for space */
    box-sizing: border-box; /* Ensure padding is included in the width */
}

.form-container {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px; /* Add margin to create space between the form and table */
}

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent white background */
    border: 2px solid rgba(255,255,255, 10);
    backdrop-filter: blur(10px); /* Apply a blur effect behind the container */
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: rgba(75, 192, 192, 20);
            color: #fff;
        }

        tbody tr:hover {
            background-color: #fffff0;
        }

        button[type="button"] {
            padding: 8px 16px;
            background-color: rgba(75, 192, 192, 20);
            color: #ffffff;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            margin-left: 10px;
        }

        button[type="button"]:hover {
            background-color: rgba(75, 192, 192, 20);
            color: #fff;
        }

        button[type="submit"] {
            padding: 8px 16px;
            background-color: rgba(75, 192, 192, 20);
            color: #ffffff;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            margin-left: 10px;
        }

        button[type="submit"]:hover {
            background-color: rgba(75, 192, 192, 20);
            color: #fff;
        }
        
</style>
