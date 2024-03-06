<?php
session_start();
$loggedInCustomerID = isset($_SESSION['CustomerID']) ? $_SESSION['CustomerID'] : null;
include("../include/config.php");
include("../general/header.php");

function getPackageInclusions($packageID) {
    global $conn;
    $inclusionsQuery = "SELECT inclusion_table.Inclusions FROM inclusion_table
                       INNER JOIN packagesinclusions ON inclusion_table.InclusionID = packagesinclusions.InclusionID
                       WHERE packagesinclusions.PackageID = $packageID";
    $inclusionsResult = $conn->query($inclusionsQuery);
    $inclusions = [];

    while ($inclusionRow = $inclusionsResult->fetch_assoc()) {
        $inclusions[] = $inclusionRow['Inclusions'];
    }

    return implode(', ', $inclusions);
}

$packagesSql = "SELECT packages.*, photographers.photographerID, photographers.name AS photographer_name, servicetypes.TypeName AS service_type_name 
                FROM packages 
                INNER JOIN photographers ON packages.photographerID = photographers.photographerID 
                INNER JOIN servicetypes ON packages.ServiceTypeID = servicetypes.ServiceTypeID";


$packagesResult = $conn->query($packagesSql);
?>




  
<section class="services">
<section class="background">
            <h4>Price and Packages</h4>
        </section>

            <div class="container">

                    <label for="service-type" >Select Service Type:</label>
                    <select id="service-type" name="service-type" class="service-type-dropdown" onchange="filterPackages()">
                        <option value="" enable selected>Select Service Type</option>

                        <?php
                        $serviceTypesSql = "SELECT * FROM servicetypes";
                        $serviceTypesResult = $conn->query($serviceTypesSql);

                        while ($serviceTypeRow = $serviceTypesResult->fetch_assoc()) {
                            $typeName = $serviceTypeRow['TypeName'];
                            echo "<option value='$typeName'>$typeName</option>";
                        }
                        ?>
                    </select>
         
         

                    <br><br>
                <p>Secure your moments, schedule your experience</p>
                <div class="service-container">
                <?php
while ($packageRow = $packagesResult->fetch_assoc()) {
    $packageID = $packageRow['PackageID'];
    $packageName = $packageRow['PackageName'];
    $photographerID = isset($packageRow['photographerID']) ? $packageRow['photographerID'] : null;
    $photographerName = $packageRow['photographer_name'];
    $packagePrice = $packageRow['Price'];
    $serviceTypeName = $packageRow['service_type_name'];

    echo '<div class="package-container" id="package-' . $packageID . '" data-service-type="' . $serviceTypeName . '">';
    echo '<h3>' . $packageName . '</h3>';
    echo '<p>Photographer: ' . $photographerName . '</p>';
    echo '<p>Price: ₱' . $packagePrice . '</p>';
    echo '<p>Service Type: ' . $serviceTypeName . '</p>';
    echo '<p>Inclusions: ' . getPackageInclusions($packageID) . '</p>';
    echo '<div class="center-button">';
    
    // Check if the user is logged in before allowing booking
    if (!isset($_SESSION['CustomerID'])) {
        echo '<button onclick="showLoginAlert()">Book an Appointment</button>';
    } else {
        // Use the bookAppointment function for logged-in users
        echo '<button onclick="bookAppointment(' . $packageID . ', ' . $photographerID . ')">Book an Appointment</button>';
    }

    echo '</div>';
    echo '</div>';
}
?>

<script>
    function showLoginAlert() {
        alert("Please login first!");
        window.location.href = "../admin/login.php";
    }

    function bookAppointment(packageID, photographerID) {
        <?php
        // If the user is logged in, proceed with booking logic
        echo 'window.location.href = "display_booking_info.php?customerID=' . $loggedInCustomerID . '&packageID=" + packageID + "&photographerID=" + photographerID;';
        ?>
    }
</script>

                </div>
            </div>
        </section>
    </div>

    <script>
    function filterPackages() {
        var selectedServiceType = document.getElementById('service-type').value;
        var packageContainers = document.querySelectorAll('.package-container');

        packageContainers.forEach(function (container) {
            var containerServiceType = container.getAttribute('data-service-type');
            container.style.display = (selectedServiceType === '' || selectedServiceType === containerServiceType) ? 'block' : 'none';
        });
    }

    // Additional scripts or functions can go here
</script>


</body>
</html>







<style>
body {
    background: linear-gradient(to bottom, #CEE6F3 ,#4F709C); /* Dark blue to light blue gradient */
    background-size: cover; /* Cover the entire background without distortion */
    background-position: center; /* Center the background image */
    background-repeat: no-repeat; /* Prevent the background from repeating */
}
    label {
        font-size: 1.5rem; 
        font-family: 'serif';
    }

    select {
    font-size: 1.5rem; /* Change the font size as needed */
    font-family: 'serif';
    }

        h4 {
            margin-top: 160px;
            font-size: 6rem;
            color: #fff;
            font-family: 'Satisfy';
        }
        h5 {
            font-size: 2rem;
            color: #333;
            font-family: 'Satisfy';
        }
        
        p {
            margin-top: 10px;
            font-size: 2.5rem;
            color: #fff;
            

        }


        .background {
            background-image: url('../uploads/cover.jpg');  /* Set the path to your cover image */
            background-size: cover;
            background-position: center bottom; /* Lower the background image */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 60%;
            z-index: -1; /* Push the background behind other content */
        }

        .container {
            margin-top: 290px;
        }
        .package-container {


            padding: 20px;

            text-align: center;
            width: calc(33.33% - 40px); 

        }

        .package-container h3 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 10px;
        }

        .package-container p {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 8px;
        }

        .service-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between; 
            margin: 0 -20px;
        }

        .center-button {
                text-align: center;
                margin-top: 20px; 
            }

            .center-button button {
                padding: 10px 20px;
                background-color: #4F709C;
                border: none;
                border-radius: 5px;
                color: #fff;
                cursor: pointer;
                font-size: 1.2rem;
                transition: background-color 0.3s ease;
            }

            .center-button button:hover {
                background-color: #2E4A6E;
            }

            .dropdown {
            display: inline-block;
        }

        .dropbtn {
            background-color: #4F709C;
            color: white;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #9BABB8;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
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

        .styled-select {
            padding: 10px;
            border: 1px solid #4F709C; 
            border-radius: 5px;
            background-color: #fff; 
            color: #4F709C; 
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
        }

        .styled-select:hover {
            border-color: #2E4A6E;
        }
</style>