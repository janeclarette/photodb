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

                <button type="submit">Create Place</button>
            </form>
        </div>
        </div>
        <table>
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

</body>
</html>


<style>

    body {
        background-color: #E0F4FF;
    }
    h2 {
        margin-top: 30px;
        text-align: center;
        color: #33;
        font-weight: bold;
        font-size: 4rem;
        font-family: 'Satisfy';
    }

    .container {
        max-width: 60%;
        margin-top: 20px;
        padding: 20px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;

        border-radius: 20px;
        margin: 0 auto; /* Centering the container */
    }

    .form-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 700px;
        margin-bottom: 20px;
        
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

    table {
        margin-left:150px;
        margin-top:40px;
        width: 80%;
        border-collapse: collapse;
        background-color: #fff;
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