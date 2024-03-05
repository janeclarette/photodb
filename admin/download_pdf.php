<?php
// Include necessary files and establish a database connection
include("../include/config.php");
// Start the session
session_start();

// Check if the form is submitted and the button for downloading PDF is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['downloadPDF'])) {
    // Include the dompdf library
    require_once '../dompdf-master/vendor/autoload.php';

    // Create a new instance of the Dompdf class
    $dompdf = new \Dompdf\Dompdf();

    // Fetch data for the PDF content using SQL queries
    $queryTotalTransactions = "SELECT COUNT(*) AS totalTransactions FROM Transactions;";
    $resultTotalTransactions = mysqli_query($conn, $queryTotalTransactions);
    $rowTotalTransactions = mysqli_fetch_assoc($resultTotalTransactions);
    $totalTransactions = (int)$rowTotalTransactions['totalTransactions'];

    // Monthly total sales
    $monthlyData = array();
    $monthlyOverallSales = array();
    $monthlyNetSales = array();
    $monthlyPhotographerEarnings = array();

    for ($month = 1; $month <= 12; $month++) {
        $startDate = "2024-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        // Monthly total transactions
        $query = "SELECT COUNT(*) AS totalTransactions
                  FROM Transactions
                  WHERE ReservationDate BETWEEN '$startDate' AND '$endDate';";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $monthlyData[$month] = (int) $row['totalTransactions'];
        } else {
            // Handle query error
            echo "Error retrieving total transactions for month $month: " . mysqli_error($conn);
        }

        // Monthly total sales
        $querySales = "SELECT COALESCE(SUM(P.Price), 0) AS totalSales
        FROM Transactions T
        JOIN Packages P ON T.PackageID = P.PackageID
        WHERE T.ReservationDate BETWEEN '$startDate' AND '$endDate'
        AND T.StatusID = '2';";
        $resultSales = mysqli_query($conn, $querySales);
        if ($resultSales) {
            $rowSales = mysqli_fetch_assoc($resultSales);
            $monthlyOverallSales[$month] = floatval($rowSales['totalSales']);
        } else {
            // Handle query error
            echo "Error retrieving total sales for month $month: " . mysqli_error($conn);
        }

        // Monthly total admin fees (Net Sales)
        $queryNetSales = "SELECT COALESCE(SUM(AdminFee), 0) AS totalAdminFees
                         FROM Transactions
                         WHERE ReservationDate BETWEEN '$startDate' AND '$endDate';";
        $resultNetSales = mysqli_query($conn, $queryNetSales);
        if ($resultNetSales) {
            $rowNetSales = mysqli_fetch_assoc($resultNetSales);
            $monthlyNetSales[$month] = floatval($rowNetSales['totalAdminFees']);
        } else {
            // Handle query error
            echo "Error retrieving total admin fees for month $month: " . mysqli_error($conn);
        }

        // Monthly total photographer earnings (Overall Sales)
        $queryOverallSales = "SELECT COALESCE(SUM(PhotographerEarning), 0) AS totalPhotographerEarnings
                             FROM Transactions
                             WHERE ReservationDate BETWEEN '$startDate' AND '$endDate';";
        $resultOverallSales = mysqli_query($conn, $queryOverallSales);
        if ($resultOverallSales) {
            $rowOverallSales = mysqli_fetch_assoc($resultOverallSales);
            $monthlyPhotographerEarnings[$month] = floatval($rowOverallSales['totalPhotographerEarnings']);
        } else {
            // Handle query error
            echo "Error retrieving total photographer earnings for month $month: " . mysqli_error($conn);
        }
    }

    // Fetch HTML content of the sales report
    ob_start();

    $html = ob_get_clean();

    // Add the sums and monthly data to the HTML content
    $html .= "<style>";
    $html .= "body { font-family: 'Roboto', sans-serif; background-color: transparent; }"; // Set background-color to transparent
    $html .= "h1 { text-align: center; font-family: 'Lobster', cursive; color: #333; }";
    $html .= "h2 { font-family: 'Lobster', cursive; color: #333; border-bottom: 2px solid #333; padding-bottom: 10px; }";
    $html .= "p { margin-bottom: 8px; }";
    $html .= "strong { color: #213555; }";
    $html .= ".total-section { margin-bottom: 20px; }";
    $html .= ".monthly-section { margin-bottom: 30px; }";
    $html .= ".header-section { text-align: center; position: relative; }";
    $html .= ".background-image { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; opacity: 0.2; }";
    $html .= ".logo { max-width: 100px; position: relative; z-index: 1; }";
    $html .= ".value { display: inline-block; min-width: 800px; }"; // Added CSS class for values
    $html .= "hr { border: 0; border-top: 1px solid #ccc; margin-top: 15px; }";
    $html .= "</style>";

    $html .= "<h1>CheeseClick Analytics Report</h1>";

    // Background Image
    $logoPath = '../uploads/C.png';
    $logoData = file_get_contents($logoPath);
    $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
    
    $html .= "<div class='header-section'>";
    $html .= "<div class='background-image' style='background-image: url($logoBase64);'></div>";

    $html .= "</div>";

    $html .= "<h2>Overall Tally:</h2>";
    $html .= "<div class='total-section'>";
    $html .= "<p><span class='value'>Total Transactions:</span> $totalTransactions Appointments</p>";
    $html .= "<p><span class='value'>Total Sales:</span> " . number_format(array_sum($monthlyOverallSales), 2) . " Pesos</p>";
    $html .= "<p><span class='value'>Total Admin Fees:</span> " . number_format(array_sum($monthlyNetSales), 2) . " Pesos</p>";
    $html .= "<p><span class='value'>Total Photographer Earnings:</span> " . number_format(array_sum($monthlyPhotographerEarnings), 2) . " Pesos</p>";
    $html .= "</div>";

    // Add monthly details
    $html .= "<h2>Monthly Details:</h2>";
    for ($month = 1; $month <= 12; $month++) {
        $html .= "<div class='monthly-section'>";
        $html .= "<p><strong>" . date('F', mktime(0, 0, 0, $month, 1)) . ":</strong></p>";
        $html .= "<p><span class='value'>Total Transactions:</span> " . $monthlyData[$month] . " Appointments</p>";
        $html .= "<p><span class='value'>Total Sales:</span> " . number_format($monthlyOverallSales[$month], 2) . " Pesos</p>";
        $html .= "<p><span class='value'>Total Admin Fees:</span> " . number_format($monthlyNetSales[$month], 2) . " Pesos</p>";
        $html .= "<p><span class='value'>Total Photographer Earnings:</span> " . number_format($monthlyPhotographerEarnings[$month], 2) . " Pesos</p>";
        $html .= "</div>";
        $html .= "<hr>";
    }

    // Load HTML content into dompdf
    $dompdf->loadHtml($html);

    // Set paper size (optional)
    $dompdf->setPaper('A4', 'landscape');

    // Render PDF (first step)
    $dompdf->render();

    // Output the generated PDF (second step - create a download link)
    $dompdf->stream('sales_report.pdf', array('Attachment' => 0));
    exit;
}
?>
