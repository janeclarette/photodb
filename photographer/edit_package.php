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

// Check if the package ID is provided in the URL
if (!isset($_GET['id'])) {
    // Redirect if the package ID is not provided
    header("Location: package.php");
    exit();
}

// Get the package ID from the URL
$packageID = $_GET['id'];

// Fetch the package details from the database
$packageQuery = "SELECT * FROM Packages WHERE PackageID = $packageID AND PhotographerID = $photographerID";
$packageResult = $conn->query($packageQuery);

if ($packageResult->num_rows == 0) {
    // Redirect if the package does not belong to the logged-in photographer
    header("Location: package.php");
    exit();
}

// Fetch package details
$packageRow = $packageResult->fetch_assoc();

// Fetch the existing inclusions for the package
$selectedInclusions = [];
$selectedInclusionsQuery = "SELECT InclusionID FROM PackagesInclusions WHERE PackageID = $packageID";
$selectedInclusionsResult = $conn->query($selectedInclusionsQuery);
if ($selectedInclusionsResult) {
    while ($row = $selectedInclusionsResult->fetch_assoc()) {
        $selectedInclusions[] = $row['InclusionID'];
    }
}

// Handle form submission for editing the package
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $packageName = $_POST["PackageName"];
    $description = $_POST["Description"];
    $price = $_POST["Price"];
    $organization = $_POST["Organization"];
    $inclusions = isset($_POST["inclusions"]) ? $_POST["inclusions"] : [];
    $serviceType = $_POST["ServiceType"];

    // Update data in the Packages table
    $updatePackageQuery = "UPDATE Packages SET 
                            PackageName = '$packageName',
                            Description = '$description',
                            Price = $price,
                            Organization = '$organization',
                            ServiceTypeID = $serviceType
                            WHERE PackageID = $packageID";
    $conn->query($updatePackageQuery);

    // Delete existing inclusions for the package
    $deleteInclusionsQuery = "DELETE FROM PackagesInclusions WHERE PackageID = $packageID";
    $conn->query($deleteInclusionsQuery);

    // Insert new inclusions for the package
    foreach ($inclusions as $inclusionID) {
        $insertInclusionQuery = "INSERT INTO PackagesInclusions (PackageID, InclusionID) VALUES ($packageID, $inclusionID)";
        $conn->query($insertInclusionQuery);
    }
    
    // Display alert and redirect using JavaScript
    echo '<script>';
    echo 'alert("Package updated successfully");';
    echo 'window.location.href = "package.php";';
    echo '</script>';
}
?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add event listeners to edit and delete buttons
        document.querySelectorAll('.edit-button').forEach(function (button) {
            button.addEventListener('click', function () {
                var packageID = this.getAttribute('data-id');
                // Implement edit functionality using AJAX
                // Send packageID to the server and handle the response
            });
        });
    });
</script>


</div>
</div>
</body>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Package</title>
</head>
<body>
    <div class="container">
        <div class="column">
            <h2>Edit Package</h2>
            <form action="" method="POST">
                <label for="PackageName">Package Name:</label>
                <input type="text" id="PackageName" name="PackageName" value="<?php echo $packageRow['PackageName']; ?>" required>

                <label for="Description">Description:</label>
                <textarea id="Description" name="Description" rows="4" required><?php echo $packageRow['Description']; ?></textarea>

                <label for="Price">Price:</label>
                <input type="number" id="Price" name="Price" step="0.01" value="<?php echo $packageRow['Price']; ?>" required>

                <label for="Organization">Organization:</label>
                <input type="text" id="Organization" name="Organization" value="<?php echo $packageRow['Organization']; ?>">

                <label for="ServiceType">Service Type:</label>
                <select id="ServiceType" name="ServiceType" required>
                    <!-- Populate the options from the database and set the selected value -->
                    <?php
                    $serviceTypesQuery = "SELECT * FROM ServiceTypes";
                    $serviceTypesResult = $conn->query($serviceTypesQuery);
                    while ($serviceTypeRow = $serviceTypesResult->fetch_assoc()) {
                        $selected = ($serviceTypeRow['ServiceTypeID'] == $packageRow['ServiceTypeID']) ? 'selected' : '';
                        echo '<option value="' . $serviceTypeRow['ServiceTypeID'] . '" ' . $selected . '>' . $serviceTypeRow['TypeName'] . '</option>';
                    }
                    ?>
                </select>

                <label for="InclusionID">Inclusions:</label>
                <?php
                $inclusionsQuery = "SELECT * FROM Inclusion_table";
                $result = $conn->query($inclusionsQuery);
                while ($row = $result->fetch_assoc()) {
                    $checked = (in_array($row['InclusionID'], $selectedInclusions)) ? 'checked' : '';
                    echo '<div class="checkbox-group">
                            <input type="checkbox" id="inclusion_' . $row['InclusionID'] . '" name="inclusions[]" value="' . $row['InclusionID'] . '" ' . $checked . '>
                            <label for="inclusion_' . $row['InclusionID'] . '">' . $row['Inclusions'] . '</label>
                        </div>';
                }
                ?>

                <input type="submit" value="Update Package">
            </form>
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
</style>