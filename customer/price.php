<?php
session_start();
$loggedInCustomerID = isset($_SESSION['CustomerID']) ? $_SESSION['CustomerID'] : null;
include("../include/config.php");

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

    <title>Customer Page</title>
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
                <a href="/photodb/customer/profile.php"> <i class="fa-regular fa-user"></i></a>
    </div>
    <div class="message">
        <a href="/photodb/customer/message.php"><i class="fa-regular fa-message"></i></a>
    </div>
    <div class="logout">
        <a href="/photodb/admin/logout.php"><i class="fas fa-sign-in-alt"></i></a>
    </div>

</div>

    
    </div>

    </header>
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



        <section class="services">
            <h2>Available Packages</h2>

            <div class="container">
                <h2>Service Type Dropdown</h2>
                    <label for="service-type">Select Service Type:</label>
                    <select id="service-type" name="service-type" class="service-type-dropdown" onchange="filterPackages()">
                        <option value="" enable selected>Select Service Type</option>

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
                  </select>
         

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
                        echo '<a href="display_booking_info.php?customerID=' . $loggedInCustomerID . '&packageID=' . $packageID . '&photographerID=' . $photographerID . '"><button>Book an Appointment</button></a>';


                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
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
        .logout {
            margin-right: 40px;
        }

        .sign-in .dropdown,
        .logout a {
            padding: 25px; 
        }

        .message{
            margin-right: 10px; 
        }

        .welcome {
            background-color: #f0f0f0;
            padding: 40px;
            margin-bottom: 20px;
            text-align: center;
            background-image: url('../uploads/cover.jpg'); 
            background-size: cover;
            background-position: center bottom; 
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome h2 {
            text-align: center;
            font-size: 6rem;
            font-family: 'Satisfy';
            color: #FEFBF6;
        }

        .services {
            background-color: #F5EFE7;
            padding: 50px;
            margin-bottom: 20px;
            text-align: center;
        }

        .services h2 {
            text-align: center;
            font-size: 3rem;
            font-family: 'Satisfy';
            color: #333;
        }
        .services h3 {
            text-align: center;
            font-size: 2rem;
            font-family: 'Satisfy';
            color: #333;
        }
        .services h6 {
            text-align: center;
            font-size: 1.5rem;
            font-family: 'Cinzel', serif;
            color: #333;
            margin: 20px;
        }
        .services p {
            text-align: center;
            font-size: 1.5rem;
            font-family: 'Cinzel', serif;
            color: #333;
        }
        .service-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .service {
            width: 200px; 
            margin: 40px; 
            text-align: center;
        }

        .service img {
            width: 150px; 
            height: auto;
            margin: 20px;
        }


        .featured-events {
            background-color: #F5EFE7;
            padding: 50px;
            margin-bottom: 20px;
            text-align: center;
        }
        .featured-events h2 {
            text-align: center;
            font-size: 3rem;
            font-family: 'Satisfy';
            color: #333;
        }
        .featured-events p {
            text-align: center;
            font-size: 1.5rem;
            font-family: 'Cinzel', serif;
            color: #333;
        }


        .package-container {
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 10px;
            padding: 20px;
            margin: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: calc(33.33% - 40px); 
            box-sizing: border-box;
            display: inline-block;
        }

        .package-container h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }

        .package-container p {
            font-size: 1.2rem;
            color: #555;
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