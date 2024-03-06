<?php
// Include necessary files and establish a database connection
include("../include/config.php");
// Start the session
session_start();

// Check if customerID is set in the session
if (!isset($_SESSION['CustomerID'])) {
    // Redirect to login page or handle the case when the customer is not logged in
    header("Location: ../customer/login.php");
    exit();
}

// Retrieve customerID from the session
$customerID = $_SESSION['CustomerID'];

// Check if TransactionID is set in the request
if (isset($_POST['TransactionID'])) {
    // Sanitize and get the TransactionID from the request
    $transactionID = mysqli_real_escape_string($conn, $_POST['TransactionID']);

    // Fetch data for the specified transaction
    $query = "SELECT t.TransactionID, t.PhotographerID, t.ReservationDate, t.Time_ID, t.PlaceID, t.PackageID, t.StatusID,
                t.TransactionDate, pt.Name, tm.start_time, tm.end_time, p.PlaceName, pk.PackageName, pk.Price, ts.StatusName
                FROM Transactions t
                JOIN photographers pt ON t.PhotographerID = pt.PhotographerID
                JOIN time tm ON t.Time_ID = tm.Time_ID
                JOIN places p ON t.PlaceID = p.PlaceID
                JOIN packages pk ON t.PackageID = pk.PackageID
                JOIN transactionstatus ts ON t.StatusID = ts.StatusID
                WHERE t.CustomerID = $customerID AND t.TransactionID = $transactionID";

    $result = mysqli_query($conn, $query);

    // Check for query execution success
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Now you have the data for the specific transaction, you can proceed with generating the PDF
        // Implement your PDF generation logic here based on the data in $row

        // For demonstration purposes, let's assume the PDF content is a simple string
        $pdfContent = "Transaction ID: {$row['TransactionID']}\n";
        $pdfContent .= "Photographer: {$row['Name']}\n";
        // Add more details as needed

        // Generate the PDF file
        $filename = "Receipt_{$row['TransactionID']}.pdf";
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo $pdfContent;
        exit();
    } else {
        // Handle error if the specified transaction is not found
        echo "Error: Transaction not found";
    }
} else {
    // Handle error if TransactionID is not set in the request
    echo "Error: TransactionID not provided";
}
?>
