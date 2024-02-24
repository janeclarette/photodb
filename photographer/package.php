<?php
// Start the session
session_start();

// Include necessary files and establish a database connection
include("../include/config.php");
include("../photographer/header.php");

// Check if the photographer is logged in
if (!isset($_SESSION['PhotographerID'])) {
    // Redirect to the login page if not logged in
    header("Location: /photodb/admin/login.php");
    exit();
}

// Get the PhotographerID from the session
$photographerID = $_SESSION['PhotographerID'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the deletePackageID is set in the POST request
    if (isset($_POST['deletePackageID'])) {
        // Retrieve the package ID to be deleted
        $packageIDToDelete = $_POST['deletePackageID'];

        // Perform deletion of the package and associated inclusions
$deletePackageQuery = "DELETE FROM Packages WHERE PackageID = $packageIDToDelete";

// Delete associated inclusions first
$deleteInclusionsQuery = "DELETE FROM PackagesInclusions WHERE PackageID = $packageIDToDelete";
if ($conn->query($deleteInclusionsQuery) === TRUE) {
    // Then delete the package
    if ($conn->query($deletePackageQuery) === TRUE) {
        // Display alert message and redirect
        echo '<script>';
        echo 'alert("Package deleted successfully");';
        echo 'window.location.href = "package.php";';
        echo '</script>';
        exit(); // Ensure no further execution of PHP code after redirection
    } else {
        echo "Error deleting package: " . $conn->error;
    }
} else {
    echo "Error deleting associated inclusions: " . $conn->error;
}
} else {
    echo "Error deleting associated transactions: " . $conn->error;
    } 

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

    
  <!-- Add your CSS stylesheets here -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Packages</title>

</head>
<body>

<div class="container">
    <div class="column">
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
<div class="column">
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
            <div class="button-group">
                <button class="edit-button" data-id="' . $packageRow['PackageID'] . '">Edit</button>
                <button class="delete-button" data-id="' . $packageRow['PackageID'] . '">Delete</button>
            </div>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add event listeners to edit and delete buttons
        document.querySelectorAll('.edit-button').forEach(function (button) {
            button.addEventListener('click', function () {
                var packageID = this.getAttribute('data-id');
                window.location.href = 'edit_package.php?id=' + packageID;
                // Implement edit functionality using AJAX
                // Send packageID to the server and handle the response
            });
        });

        document.querySelectorAll('.delete-button').forEach(function (button) {
            button.addEventListener('click', function () {
                var packageID = this.getAttribute('data-id');
                if (confirm("Are you sure you want to delete this package?")) {
                    // Send packageID to the server for deletion using form submission
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '';
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'deletePackageID';
                    input.value = packageID;
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>


</div>
</div>
</body>
</html>


<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Apply font family and styles */
        body {
            font-family: serif;
            background-color: #E0F4FF;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin-top: 20px;

        }

        .column {
            flex: 1;
            margin: 0 10px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            width: 600px;
        }

        /* Apply styles for form elements */
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: calc(100% - 20px); /* Adjust width to leave space for padding */
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
        h2 {
            text-align: center;
            font-size: 2rem;
            font-family: 'Satisfy';
            color: #333;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        h3 {
            text-align: center;
            font-size: 1.4rem;
            font-family: 'Satisfy';
            color: #333;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        p {
            text-align: center;
            font-size: 1.3rem;
            font-family:  serif;
            color: #333;
            margin-top: 30px;
        }
        .edit-button,
        .delete-button {
            background-color: #4F709C;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 8px; /* Add margin between buttons */
        }

        .edit-button:hover,
        .delete-button:hover {
            background-color: #345981;
        }

        .button-group {
            margin-top: 10px; /* Add margin between button group and package details */
            text-align: center;
        }
</style>