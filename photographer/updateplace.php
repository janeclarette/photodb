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
    // Update the place based on the form data
    $placeID = mysqli_real_escape_string($conn, $_POST["placeID"]);
    $placeName = mysqli_real_escape_string($conn, $_POST["placeName"]);
    $address = mysqli_real_escape_string($conn, $_POST["address"]);
    $cityID = mysqli_real_escape_string($conn, $_POST["city"]);

    $updatePlaceQuery = "UPDATE Places SET PlaceName='$placeName', Address='$address', CityID='$cityID' WHERE PlaceID='$placeID'";
    if ($conn->query($updatePlaceQuery)) {
        echo '<script>';
        echo 'alert("Place updated successfully");';
        echo 'window.location.href = "place.php";';
        echo '</script>';
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch the details of the selected place
if (isset($_GET["id"])) {
    $placeID = mysqli_real_escape_string($conn, $_GET["id"]);
    $selectPlaceQuery = "SELECT PlaceID, PlaceName, Address, CityID FROM Places WHERE PlaceID='$placeID'";
    $result = $conn->query($selectPlaceQuery);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $placeName = $row["PlaceName"];
        $address = $row["Address"];
        $cityID = $row["CityID"];
    } else {
        echo "Place not found";
        exit();
    }
} else {
    echo "Invalid request";
    exit();
}
?>

<body>
    <h2>Update Place</h2>
    <div class="container">
        <div class="form-container">
            <form method="post" action="">
                <input type="hidden" name="placeID" value="<?php echo $placeID; ?>">

                <label for="placeName">Place Name:</label>
                <input type="text" id="placeName" name="placeName" value="<?php echo $placeName; ?>" required>

                <label for="address">Address:</label>
                <textarea id="address" name="address" required><?php echo $address; ?></textarea>

                <label for="city">City:</label>
                <select id="city" name="city" required>
                    <option value="" disabled>Select your City</option>
                    <?php
                    $cityQuery = "SELECT CityID, CityName FROM Cities";
                    $result = $conn->query($cityQuery);

                    if ($result->num_rows > 0) {
                        while ($cityRow = $result->fetch_assoc()) {
                            $selected = ($cityID == $cityRow["CityID"]) ? "selected" : "";
                            echo '<option value="' . $cityRow["CityID"] . '" ' . $selected . '>' . $cityRow["CityName"] . '</option>';
                        }
                    }
                    ?>
                </select>

                <button type="submit">Update Place</button>
            </form>
        </div>
    </div>
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