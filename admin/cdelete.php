<?php
include("../include/config.php");

// Check if the ID parameter is set in the URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete_id'])) {
    // Sanitize the input to prevent SQL injection
    $customer_id = mysqli_real_escape_string($conn, $_GET['delete_id']);

    // Construct the delete query
    $deleteCustomerQuery = "DELETE FROM customers WHERE CustomerID = $customer_id";

    // Perform the deletion and handle errors
    if (mysqli_query($conn, $deleteCustomerQuery)) {
        // If deletion is successful, redirect to customers.php
        header("Location: customers.php");
        exit();
    } else {
        // If there's an error, display an error message
        echo "Error deleting customer: " . mysqli_error($conn);
    }
} else {
    // If the ID parameter is not set, display a message indicating that the customer was not found
    echo "Customer not found";
}

// Close the database connection
$conn->close();
?>
