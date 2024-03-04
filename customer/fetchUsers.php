<?php
// Assuming you have a database connection established
include("../include/config.php");

// Get the selected user type from the URL parameter
$selectedType = isset($_GET['type']) ? $_GET['type'] : '';

// Initialize an empty array to store the results
$results = array();

// Perform a query based on the selected type
switch ($selectedType) {
    case 'admin':
        $query = "SELECT AdminID AS ID, Name FROM Admin";
        break;
    case 'photographer':
        $query = "SELECT PhotographerID AS ID, Name FROM Photographers";
        break;
    case 'customer':
        $query = "SELECT CustomerID AS ID, Name FROM Customers";
        break;
    default:
        // Handle other cases or provide a default query
        break;
}

// Execute the query
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    // Fetch data and add it to the results array
    while ($row = mysqli_fetch_assoc($result)) {
        $results[] = $row;
    }

    // Free the result set
    mysqli_free_result($result);
} else {
    // Handle query errors
    die(mysqli_error($conn));
}

// Output the results as JSON
header('Content-Type: application/json');
echo json_encode($results);

// Close the database connection (assuming it's stored in $conn)
mysqli_close($conn);
?>
