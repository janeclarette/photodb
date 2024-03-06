<?php
// Include database connection and other necessary files
include("../include/config.php");
include("../admin/adminheader.php");

// Monthly data and total sales variables
$monthlyData = array();
$monthlySales = array();
$monthlyNetSales = array();
$monthlyOverallSales = array();

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
        $monthlySales[$month] = floatval($rowSales['totalSales']);
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
        $monthlyOverallSales[$month] = floatval($rowOverallSales['totalPhotographerEarnings']);
    } else {
        // Handle query error
        echo "Error retrieving total photographer earnings for month $month: " . mysqli_error($conn);
    }
}

// Convert data to JSON for use in JavaScript
$monthlyDataJSON = json_encode(array_values($monthlyData));
$monthlySalesJSON = json_encode(array_values($monthlySales));
$monthlyNetSalesJSON = json_encode(array_values($monthlyNetSales));
$monthlyOverallSalesJSON = json_encode(array_values($monthlyOverallSales));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report 2024</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f0f0; /* Set a background color */
        }

        .container {
            width: 80%;
            margin: auto;
            background-color: white; /* Set a background color */
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #chartControls {
            margin-bottom: 20px;
        }

        #salesChart {
            margin-bottom: 20px;
        }

        #chartInfo {
            display: none;
        }
        #downloadPdfBtn {
    background-color: #213555; /* Light blue color */
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin-top: 20px;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

#downloadPdfBtn:hover {
    background-color: slategray; /* Slightly darker shade on hover */
}
    </style>
</head>
<body>
    <div class="container">
        <div id="chartControls">
            <label for="dataType">Select Data Type:</label>
            <select id="dataType" onchange="updateChart()">
                <option value="totalTransactions">Total Transactions</option>
                <option value="monthlySales">Total Sales</option>
                <option value="netSales">Admin Profit</option>
                <option value="overallSales">Photographer Revenue</option>
            </select>
        </div>

        <canvas id="salesChart"></canvas>

        <div id="chartInfo">
            <h2 id="chartLabel"></h2>
            <p id="chartValue"></p>
        </div>

        <div id="downloadPdfBtnContainer">
            <!-- Add the PDF download button -->
            <form action="download_pdf.php" method="post">
                <button type="submit" name="downloadPDF" id="downloadPdfBtn">
                    Download PDF
                </button>
            </form>
        </div>
    </div>

    <script>
        var monthlyData = <?php echo $monthlyDataJSON; ?>;
        var monthlySalesData = <?php echo $monthlySalesJSON; ?>;
        var monthlyNetSalesData = <?php echo $monthlyNetSalesJSON; ?>;
        var monthlyOverallSalesData = <?php echo $monthlyOverallSalesJSON; ?>;

        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Total Transactions',
                    data: monthlyData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function updateChart() {
            var dataType = document.getElementById('dataType').value;
            var chartLabelElement = document.getElementById('chartLabel');
            var chartValueElement = document.getElementById('chartValue');
            var chartInfoElement = document.getElementById('chartInfo');

            if (dataType === 'totalTransactions') {
                salesChart.data.datasets[0].label = 'Total Transactions';
                salesChart.data.datasets[0].data = monthlyData;
                chartLabelElement.innerHTML = 'Total Transactions';

                var totalTransactions = monthlyData.reduce((a, b) => a + b, 0);
                chartValueElement.innerHTML = 'Total Transactions: ' + totalTransactions;
            } else if (dataType === 'monthlySales') {
                salesChart.data.datasets[0].label = 'Total Sales';
                salesChart.data.datasets[0].data = monthlySalesData;
                chartLabelElement.innerHTML = 'Sales';

                var totalSales = parseFloat(monthlySalesData.reduce((a, b) => a + b, 0)).toFixed(2);
                chartValueElement.innerHTML = 'Total Sales: ₱' + totalSales;
            } else if (dataType === 'netSales') {
                salesChart.data.datasets[0].label = 'Admin Profit';
                salesChart.data.datasets[0].data = monthlyNetSalesData;
                chartLabelElement.innerHTML = 'Admin Profit';

                var totalNetSales = parseFloat(monthlyNetSalesData.reduce((a, b) => a + b, 0)).toFixed(2);
                chartValueElement.innerHTML = 'Profit: ₱' + totalNetSales;
            } else if (dataType === 'overallSales') {
                salesChart.data.datasets[0].label = 'Photographer Revenue';
                salesChart.data.datasets[0].data = monthlyOverallSalesData;
                chartLabelElement.innerHTML = 'Photographer Revenue';

                var totalOverallSales = parseFloat(monthlyOverallSalesData.reduce((a, b) => a + b, 0)).toFixed(2);
                chartValueElement.innerHTML = 'Revenue: ₱' + totalOverallSales;
            }

            salesChart.update();
            chartInfoElement.style.display = 'block';
        }
    </script>
</body>
</html>
