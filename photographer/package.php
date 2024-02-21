<?php
// Start the session
session_start();

// Include necessary files and establish a database connection
include("../include/config.php");

// Check if the photographer is logged in
if (!isset($_SESSION['PhotographerID'])) {
    // Redirect to the login page if not logged in
    header("Location: /photodb/login.php");
    exit();
}

// Get the PhotographerID from the session
$photographerID = $_SESSION['PhotographerID'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $packageName = $_POST["PackageName"];
    $description = $_POST["Description"];
    $price = $_POST["Price"];
    $organization = $_POST["Organization"];
    $inclusions = isset($_POST["inclusions"]) ? $_POST["inclusions"] : [];
    $serviceType = $_POST["ServiceType"];

    // Insert data into the Packages table
    $insertPackageQuery = "INSERT INTO Packages (PhotographerID, PackageName, Description, ServiceTypeID, Price, Organization)
                         VALUES ($photographerID, '$packageName', '$description', $serviceType, $price, '$organization')";
    $conn->query($insertPackageQuery);

    // Retrieve the last inserted PackageID
    $packageID = $conn->insert_id;

    // Insert data into the PackagesInclusions table for each selected inclusion
    foreach ($inclusions as $inclusionID) {
        $insertInclusionQuery = "INSERT INTO PackagesInclusions (PackageID, InclusionID) VALUES ($packageID, $inclusionID)";
        $conn->query($insertInclusionQuery);
    }
    
    // Display alert and redirect using JavaScript
    echo '<script>';
    echo 'alert("Package created successfully");';
    echo 'window.location.href = "package.php";';
    echo '</script>';
}
?>
    <title>Photographer Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
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
        <div class="profile">
    <!-- Profile (upper right corner) -->
    <div class="sign-in">
    <a href="phprofile.php?photographerID=?"><i class="fa-regular fa-user"></i></a>
    </div>
    <div class="message">
        <!-- Logout link -->
        <a href="/photodb/photographer/message.php"><i class="fa-regular fa-message"></i></a>
    </div>
    <div class="logout">
        <!-- Logout link -->
        <a href="/photodb/admin/logout.php"><i class="fas fa-sign-in-alt"></i></a>
    </div>

</div>

    
    </div>

    </header>
    <!-- Secondary navigation bar -->
    <nav class="sub-navbar">
        <ul>
            <!-- Navigation links -->
            <li><a href="phdashboard.php">Home</a></li>
            <li><a href="work_create.php">Portfolio</a></li>
            <li><a href="schedule.php">Schedule</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="package.php">Package</a></li>
            <li><a href="place.php">Place</a></li>
            <li><a href="#">Reviews</a></li>
        </ul>
    </nav>
    
  <!-- Add your CSS stylesheets here -->
  <style>
        /* Resetting default margin and padding */
        body, h1, h2, h3, h4, h5, h6, p, ul, ol, li, figure, figcaption, blockquote, dl, dd, dt {
            margin: 0;
            padding: 0;
        }

        /* Add your custom styles for the header and navigation bars */
        .navbar {
            /* Styles for the main navigation bar */
            background-color: #213555;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo img {
            margin-left: 40px;
            height: 80px; /* Adjust as needed */
            width: auto; /* Ensures the image scales with height */
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
            /* Styles for the secondary navigation bar */
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
          /* Dropdown menu */
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
        /* Container for sections */
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
            margin-right: 40px; /* Adjust the margin between the items */
        }

        .sign-in .dropdown,
        .logout a {
            padding: 25px; /* Adjust the padding for better spacing */
        }

        .message{
            margin-right: 10px; /* Adjust the margin between the items */
        }
        input[type="text"],
input[type="number"],
textarea,
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Style for checkboxes */
.checkbox-group {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}

input[type="checkbox"] {
    margin-right: 8px;
}

/* Style for the submit button */
input[type="submit"] {
    background-color: #4F709C;
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #345981;
}

.package-container {
        border: 1px solid #ccc;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 10px;
    }

    .package-container h3 {
        color: #4F709C;
    }

    .package-container p {
        margin-bottom: 10px;
    }
        </style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Packages</title>
    <!-- Add your stylesheet link here -->
    <link rel="stylesheet" href="your-stylesheet.css">
</head>
<body>

<div class="container">
    <h2>Create a New Package</h2>
    <form action="" method="POST">
        <label for="PackageName">Package Name:</label>
        <input type="text" id="PackageName" name="PackageName" required>

        <label for="Description">Description:</label>
        <textarea id="Description" name="Description" rows="4" required></textarea>

        <label for="Price">Price:</label>
        <input type="number" id="Price" name="Price" step="0.01" required>

        <label for="Organization">Organization:</label>
        <input type="text" id="Organization" name="Organization">

        <label for="ServiceType">Service Type:</label>
        <select id="ServiceType" name="ServiceType" required>
        <option value="" disabled selected>Select your Service Type</option>
            <?php
            // Your SQL query for fetching ServiceTypes
            $serviceTypesQuery = "SELECT * FROM ServiceTypes";

            // Execute the query
            $serviceTypesResult = $conn->query($serviceTypesQuery);

            // Display options for each ServiceType
            while ($serviceTypeRow = $serviceTypesResult->fetch_assoc()) {
                echo '<option value="' . $serviceTypeRow['ServiceTypeID'] . '">' . $serviceTypeRow['TypeName'] . '</option>';
            }
            ?>
        </select>

        <label for="InclusionID">Inclusions:</label>
        <?php
        // Your SQL query for fetching Inclusions
        $inclusionsQuery = "SELECT * FROM Inclusion_table";

        // Execute the query
        $result = $conn->query($inclusionsQuery);

        // Display checkboxes for each inclusion
        while ($row = $result->fetch_assoc()) {
            echo '<div class="checkbox-group">
                    <input type="checkbox" id="inclusion_' . $row['InclusionID'] . '" name="inclusions[]" value="' . $row['InclusionID'] . '">
                    <label for="inclusion_' . $row['InclusionID'] . '">' . $row['Inclusions'] . '</label>
                  </div>';
        }
        ?>

        <input type="submit" value="Create Package">
        
    </form>
</div>
<div class="container">
    <h2>Existing Packages</h2>

    <?php
    // Fetch and display existing packages
    $existingPackagesQuery = "SELECT * FROM Packages WHERE PhotographerID = $photographerID";
    $existingPackagesResult = $conn->query($existingPackagesQuery);

    while ($packageRow = $existingPackagesResult->fetch_assoc()) {
        echo '<div class="package-container">
                <h3>' . $packageRow['PackageName'] . '</h3>
                <p>Description: ' . $packageRow['Description'] . '</p>
                <p>Price: ₱' . number_format($packageRow['Price'], 2) . '</p>
                <p>Service Type: ' . getServiceTypeName($packageRow['ServiceTypeID']) . '</p>
                <p>Inclusions: ' . getPackageInclusions($packageRow['PackageID']) . '</p>
              </div>';
    }

    // Function to get Service Type Name
    function getServiceTypeName($serviceTypeID) {
        global $conn;
        $serviceTypeQuery = "SELECT TypeName FROM ServiceTypes WHERE ServiceTypeID = $serviceTypeID";
        $serviceTypeResult = $conn->query($serviceTypeQuery);
        $serviceTypeRow = $serviceTypeResult->fetch_assoc();
        return $serviceTypeRow['TypeName'];
    }
// Function to get Package Inclusions
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
?>

</div>
</body>
</html>