<?php
session_start();
$loggedInCustomerID = isset($_SESSION['CustomerID']) ? $_SESSION['CustomerID'] : null;

if (!$loggedInCustomerID) {
    header("Location: /photodb/customer/login.php"); 
    exit();
}

include("../include/config.php");

// Fetch booking information
$customerID = $loggedInCustomerID;
$packageID = isset($_GET['packageID']) ? $_GET['packageID'] : null;
$photographerID = isset($_GET['photographerID']) ? $_GET['photographerID'] : null;

// Fetch customer name
$customerQuery = "SELECT name FROM customers WHERE CustomerID = $customerID";
$customerResult = mysqli_query($conn, $customerQuery);
$customerName = ($row = mysqli_fetch_assoc($customerResult)) ? $row['name'] : '';

// Fetch package name
$packageQuery = "SELECT packageName FROM packages WHERE packageID = $packageID";
$packageResult = mysqli_query($conn, $packageQuery);
$packageName = ($row = mysqli_fetch_assoc($packageResult)) ? $row['packageName'] : '';

// Fetch photographer name
$photographerQuery = "SELECT name FROM photographers WHERE PhotographerID = $photographerID";
$photographerResult = mysqli_query($conn, $photographerQuery);
$photographerName = ($row = mysqli_fetch_assoc($photographerResult)) ? $row['name'] : '';

// Fetch available dates
$currentDate = date('Y-m-d');
$availabilityQuery = "SELECT date_id FROM availability_schedule WHERE photographerid = $photographerID AND schedule_status_id = 1 AND date_id >= '$currentDate'";
$availabilityResult = mysqli_query($conn, $availabilityQuery);
$availableDates = [];

while ($row = mysqli_fetch_assoc($availabilityResult)) {
    $availableDates[] = $row['availability_date'];
}

$photographerPlaceQuery = "SELECT placeid, placename FROM places WHERE photographerid = $photographerID";
$photographerPlaceResult = mysqli_query($conn, $photographerPlaceQuery);
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Information</title>
    <link rel="stylesheet" href="style.css"> 
<style>
            h2 {
            text-align: center;
            color: #F3EEEA;
            font-weight: bold;
            font-size: 3rem;
            font-family: 'Satisfy';
            margin-bottom: 20px;
        }

        body {
            background-image: url('../uploads/b.jpg');
            background-size: cover;
            background-attachment: fixed;
            height: 100vh;
            margin-top: 30px;
            margin-left: 10px;
        }

        .form-outline {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
        }

        .form-outline label {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }

        .form-control {
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
        }

        .form-control:focus {
            outline: none;
            border-color: #B0A695;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4F709C;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            display: block;
            margin: 20px auto;
            width: 200px;
        }

        .btn:hover {
            background-color: #9B8E7B;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 0 0 calc(50% - 20px);
            margin-right: 30px;
        }

        .form-group:last-child {
            margin-right: 0;
        }
</style>
</head>

<body>
    <h2>Booking Information</h2>
    <form action="bookingstore.php" method="post" class="form-outline" enctype="multipart/form-data" onsubmit="return confirmBooking();">
        <div class="form-row">
            <div class="form-group">
                <label for="customerID">Customer Name:</label><br>
                <input type="text" id="customerID" name="customerID" class="form-control" value="<?php echo $customerName; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="packageID">Package Name:</label><br>
                <input type="text" name="packageID" value="<?php echo $packageID; ?>" style="display: none;">
                <input type="text" value="<?php echo $packageName; ?>" readonly>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="photographerID">Photographer Name:</label><br>
                <input type="text" name="photographerID" value="<?php echo $photographerID; ?>" style="display: none;">
                <input type="text" value="<?php echo $photographerName; ?>" readonly>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="bookingDate">Select Booking Date:</label><br>
                <select id="bookingDate" name="bookingDate" class="form-control" required>
                    <option value="" disabled selected>Select Date</option>
                    <?php
                    foreach ($availableDates as $date) {
                        echo "<option value='{$date}'>{$date}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="bookingTime">Enter Booking Time:</label><br>
                <input type="text" id="bookingTime" name="bookingTime" class="form-control" placeholder="HH:mm AM/PM" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="bookingLocation">Select Booking Location:</label><br>
                <div>
                    <input type="radio" id="photographerPlace" name="bookingLocation" value="photographer" checked>
                    <label for="photographerPlace">Photographer's Place</label>
                </div>
                <div>
                    <input type="radio" id="customerPlace" name="bookingLocation" value="customer">
                    <label for="customerPlace">Customer's Place</label>
                </div>
            </div>
        </div>
        <div class="form-row" id="photographerPlaceInput">
            <div class="form-group">
                <label for="photographerLocation">Select Photographer's Place:</label><br>
                <select id="photographerLocation" name="photographerLocation" class="form-control">
                    <?php
                    while ($row = mysqli_fetch_assoc($photographerPlaceResult)) {
                        echo "<option value='{$row['placeid']}'>{$row['placename']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-row" id="customerPlaceInput" style="display: none;">
            <div class="form-group">
                <label for="customerPlaceName">Customer's Place Name:</label><br>
                <input type="text" id="customerPlaceName" name="customerPlaceName" class="form-control">
            </div>
            <div class="form-group">
                <label for="customerPlaceAddress">Customer's Place Address:</label><br>
                <input type="text" id="customerPlaceAddress" name="customerPlaceAddress" class="form-control">
            </div>
        </div>
        <button type="submit"  class="btn">Book Schedule</button>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const photographerPlaceInput = document.getElementById('photographerPlaceInput');
            const customerPlaceInput = document.getElementById('customerPlaceInput');
            const photographerPlaceRadio = document.getElementById('photographerPlace');
            const customerPlaceRadio = document.getElementById('customerPlace');


            function updateVisibility() {
                if (photographerPlaceRadio.checked) {
                    photographerPlaceInput.style.display = 'block';
                    customerPlaceInput.style.display = 'none';
                } else if (customerPlaceRadio.checked) {
                    photographerPlaceInput.style.display = 'none';
                    customerPlaceInput.style.display = 'block';
                }
            }

            updateVisibility();
            photographerPlaceRadio.addEventListener('change', updateVisibility);
            customerPlaceRadio.addEventListener('change', updateVisibility);
        });
    </script>

    <script>
        function confirmBooking() {
            var confirmBooking = confirm("Are you sure you want to book this schedule?");
            
            if (confirmBooking) {
                return true;
            } else {
                return false;
            }
        }
    </script>

</body>
</html>