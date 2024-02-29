<?php
include("../include/config.php");
include("../photographer/header.php");

// Monthly data and most booked photographer variables
$monthlyData = array();
$mostBookedPhotographer = array('name' => '', 'bookings' => 0);
$monthlySales = array();

for ($month = 1; $month <= 12; $month++) {
    $startDate = "2024-$month-01";
    $endDate = date('Y-m-t', strtotime($startDate));

    // Monthly total transactions
    $query = "SELECT COUNT(*) AS totalTransactions
              FROM Transactions
              WHERE ReservationDate BETWEEN '$startDate' AND '$endDate';";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $monthlyData[$month] = $row['totalTransactions'];

    // Monthly total sales
    $querySales = "SELECT SUM(Price) AS totalSales
                   FROM Transactions T
                   JOIN Packages P ON T.PackageID = P.PackageID
                   WHERE T.ReservationDate BETWEEN '$startDate' AND '$endDate';";
    $resultSales = mysqli_query($conn, $querySales);
    $rowSales = mysqli_fetch_assoc($resultSales);
    $monthlySales[$month] = $rowSales['totalSales'];

    // Most booked photographer
    $queryPhotographer = "SELECT P.Name AS PhotographerName, COUNT(*) AS bookings
                          FROM Transactions T
                          JOIN Photographers P ON T.PhotographerID = P.PhotographerID
                          WHERE T.ReservationDate BETWEEN '$startDate' AND '$endDate'
                          GROUP BY P.PhotographerID
                          ORDER BY bookings DESC
                          LIMIT 1;";
    $resultPhotographer = mysqli_query($conn, $queryPhotographer);
    $rowPhotographer = mysqli_fetch_assoc($resultPhotographer);

    if ($rowPhotographer['bookings'] > $mostBookedPhotographer['bookings']) {
        $mostBookedPhotographer['name'] = $rowPhotographer['PhotographerName'];
        $mostBookedPhotographer['bookings'] = $rowPhotographer['bookings'];
    }
}

// Close your database connection
mysqli_close($conn);

// Convert data to JSON for use in JavaScript
$monthlyDataJSON = json_encode(array_values($monthlyData));
$monthlySalesJSON = json_encode(array_values($monthlySales));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report 2024</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="width: 80%; margin: auto;">
        <!-- Dropdown menu for selecting data type -->
        <select id="dataType" onchange="updateChart()">
            <option value="totalTransactions">Total Transactions</option>
            <option value="mostBookedPhotographer">Most Booked Photographer</option>
            <option value="monthlySales">Monthly Sales</option>
        </select>
        <canvas id="salesChart"></canvas>
        <!-- Add the following HTML code within the body tag -->
        <div>
            <h2 id="chartLabel">Total Transactions</h2>
            <p id="chartValue">Total Transactions: <?php echo $mostBookedPhotographer['bookings']; ?></p>
        </div>
    </div>

    <script>
        // Parse the JSON data
        var monthlyData = <?php echo $monthlyDataJSON; ?>;
        var mostBookedPhotographerData = [<?php echo $mostBookedPhotographer['bookings']; ?>];
        var monthlySalesData = <?php echo $monthlySalesJSON; ?>;

        // Create a bar chart using Chart.js
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

        // Update chart data based on selected option
        function updateChart() {
            var dataType = document.getElementById('dataType').value;
            var chartLabelElement = document.getElementById('chartLabel');
            var chartValueElement = document.getElementById('chartValue');

            if (dataType === 'totalTransactions') {
                salesChart.data.datasets[0].label = 'Total Transactions';
                salesChart.data.datasets[0].data = monthlyData;
                chartLabelElement.innerHTML = 'Total Transactions';
                chartValueElement.innerHTML = 'Total Transactions: ' + monthlyData.reduce((a, b) => a + b, 0);
            } else if (dataType === 'mostBookedPhotographer') {
                salesChart.data.datasets[0].label = 'Most Booked Photographer';
                salesChart.data.datasets[0].data = mostBookedPhotographerData;
                chartLabelElement.innerHTML = 'Most Booked Photographer';
                chartValueElement.innerHTML = 'Total Bookings: ' + mostBookedPhotographerData[0];
            } else if (dataType === 'monthlySales') {
                salesChart.data.datasets[0].label = 'Monthly Sales';
                salesChart.data.datasets[0].data = monthlySalesData;
                chartLabelElement.innerHTML = 'Monthly Sales';
                chartValueElement.innerHTML = 'Total Sales: $' + monthlySalesData.reduce((a, b) => a + b, 0);
            }

            salesChart.update();
        }
    </script>
</body>
</html>
