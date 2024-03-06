<?php
// Include necessary files and establish a database connection
include("../include/config.php");
// Start the session
session_start();
// ...

// Check if the form is submitted and the button for downloading PDF is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['downloadPDF'])) {
    // Include the dompdf library
    require_once '../dompdf-master/vendor/autoload.php';

    // Create a new instance of the Dompdf class
    $dompdf = new \Dompdf\Dompdf();

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
                    WHERE t.TransactionID = $transactionID";

        $result = mysqli_query($conn, $query);

        // Check for query execution success
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Now you have the data for the specific transaction, you can proceed with generating the PDF
            // Implement your PDF generation logic here based on the data in $row

        // Update the CSS styles for a more aesthetic look
$logoPath = '../uploads/C.png';
$logoData = file_get_contents($logoPath);
$logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);

// ...

$pdfContent = "
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #FFF; 
        }
        .receipt-container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 2px solid #213555; /* Different shade of blue border */
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #213555; /* Different shade of blue for the header */
            margin-bottom: 20px;
        }
        .logo {
            position: fixed; top: 12%; left: 36%; width: 100%; height: 100%; opacity: 0.5; z-index: -1;'/>
        }
        .logo img {
            max-width: 220px;
        }
        
        .receipt-details {
            margin-bottom: 20px;
            border-bottom: 1px dashed #213555;
            padding-bottom: 10px;
        }
        .receipt-details p {
            margin: 8px 0; /* Increased margin for better spacing */
            font-size: 18px; /* Larger font size for better readability */
            font-family : 'serif'; /* Use a more common font */
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 16px; /* Adjusted font size for the footer */
            color: #555; /* Slightly darker color for the footer */
        }
        p span.value {
            display: inline-block;
            min-width: 380px;
        }
    </style>
    <div class='receipt-container'>
        <div class='logo'>
            <img src='{$logoBase64}' alt='Logo'>
        </div>
        <div class='header'>Receipt</div>
        <div class='receipt-details'>
            <p><span class='value'>Transaction ID:</span> {$row['TransactionID']}</p>
            <p><span class='value'>Photographer:</span> {$row['Name']}</p>
            <p><span class='value'>Reservation Date:</span> {$row['ReservationDate']}</p>
            <p><span class='value'>Time:</span> {$row['start_time']} - {$row['end_time']}</p>
            <p><span class='value'>Place:</span> {$row['PlaceName']}</p>
            <p><span class='value'>Package:</span> {$row['PackageName']}</p>
            <p><span class='value'>Price:</span> {$row['Price']}</p>
            <p><span class='value'>Transaction Status:</span> {$row['StatusName']}</p>
            <p><span class='value'>Transaction Date:</span> {$row['TransactionDate']}</p>
        </div>
        <div class='footer'>Thank you for choosing our service!</div>
    </div>
";

// ...

            // Load HTML content into dompdf
            $dompdf->loadHtml($pdfContent);

            // Set paper size (optional)
            $dompdf->setPaper('A4', 'portrait');

            // Render PDF (first step)
            $dompdf->render();

            // Output the generated PDF (second step - create a download link)
            $dompdf->stream("Receipt_{$row['TransactionID']}.pdf", array('Attachment' => 0));
            exit();
        } else {
            // Handle error if the specified transaction is not found
            echo "Error: Transaction not found";
        }
    } else {
        // Handle error if TransactionID is not set in the request
        echo "Error: TransactionID not provided";
    }
}
?>
