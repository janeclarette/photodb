<?php
session_start();
include("../include/config.php");
include("../admin/adminheader.php");

// Fetch photographer bookings
$bookings_query = "SELECT PhotographerID, COUNT(*) AS Bookings FROM transactions GROUP BY PhotographerID";
$bookings_result = mysqli_query($conn, $bookings_query);

// Check for query execution errors
if (!$bookings_result) {
    die("Photographer bookings query failed: " . mysqli_error($conn));
}

// Initialize arrays to store photographer IDs and their respective bookings
$photographerIDs = array();
$photographerBookings = array();

// Fetch photographer IDs and their respective bookings
while ($booking = mysqli_fetch_assoc($bookings_result)) {
    $photographerIDs[] = $booking['PhotographerID'];
    $photographerBookings[] = $booking['Bookings'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photographers</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class='cover-section'><h2>Photographers</h2></div>

    <canvas id="photographerChart"></canvas>

    <script>
        // Parse PHP variables into JavaScript
        var photographerIDs = <?php echo json_encode($photographerIDs); ?>;
        var photographerBookings = <?php echo json_encode($photographerBookings); ?>;

        // Create a bar chart using Chart.js
        var ctx = document.getElementById('photographerChart').getContext('2d');
        var photographerChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: photographerIDs,
                datasets: [{
                    label: 'Bookings',
                    data: photographerBookings,
                    backgroundColor: '#9BABB8',
                    borderColor: '#2D4356',
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
    </script>

    <?php
    // Fetch photographers
    $photographers_query = "SELECT * FROM photographers";
    $photographers_result = mysqli_query($conn, $photographers_query);

    // Check for query execution errors
    if (!$photographers_result) {
        die("Photographer query failed: " . mysqli_error($conn));
    }
    ?>
 <div class="description">
        <?php
        // Query to fetch the most booked photographer
        $query = "SELECT P.Name AS PhotographerName, COUNT(*) AS bookings
                  FROM Transactions T
                  JOIN Photographers P ON T.PhotographerID = P.PhotographerID
                  GROUP BY P.PhotographerID
                  ORDER BY bookings DESC
                  LIMIT 1";
        $result = mysqli_query($conn, $query);

        // Check if the query was successful
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $mostBookedPhotographerName = $row['PhotographerName'];
            $mostBookedPhotographerBookings = $row['bookings'];
            echo "<h3>Most Booked Photographer:</h3>";
            echo "<p>Name: $mostBookedPhotographerName</p>";
            echo "<p>Bookings: $mostBookedPhotographerBookings</p>";
        } else {
            echo "<p>No data available</p>";
        }
        ?>
    </div>
    <?php
    // Loop through the photographer data and display each in a separate div
    while ($photographer = mysqli_fetch_assoc($photographers_result)) {
    ?>
        <div class='book-card'>
            <?php
            // Display the photographer image if available
            if ($photographer['img_photographer']) {
                echo "<img src='../uploads/{$photographer['img_photographer']}' alt='Photographer Image' class='photographer-image'>";
            } else {
                echo "<p>No image available</p>";
            }
            ?>
            <p style="font-family: 'Satisfy'; font-size:1.5rem;"><strong><?php echo $photographer['Name']; ?></strong></p>
            <p>Address: <?php echo $photographer['Address']; ?></p>
            <p>Contact Number: <?php echo $photographer['Phone_Number']; ?></p>
            <p>Email: <?php echo $photographer['Email']; ?></p>
            <!-- Additional fields display if needed -->
            <p class='actions'>
                <a href='pdelete.php?delete_id=<?php echo $photographer['PhotographerID']; ?>' class='delete-link'>Delete Photographer</a>
            </p>
        </div>
        
    <?php
    }
    ?>

</body>
</html>

<style>
    body {
        background-image: url('../uploads/b.jpg');
        background-size: cover;
        background-attachment: fixed;
        height: 100vh;
    }

    .description {
            margin-top: 20px;
            margin-left: 100px;
            padding: 20px;
            max-width: 400px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .description h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }

        .description p {
            font-size: 1rem;
            color: #555;
            margin-bottom: 5px;
        }
        
    h2 {
        margin-top: 20px;
        margin-bottom: 70px;
        text-align: center;
        color: #fff;
        font-weight: bold;
        font-size: 6rem;
        font-family: 'Satisfy';
    }

    .cover-section {
        margin-top: 20px;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .book-card {
        background-color: #E0F4FF;
        text-align: center; /* Center the book grid */
        margin-left: 50px;
        margin-right: 50px;
        margin-top: 50px;
        padding: 50px;
        border-radius: 20px;
        display: inline-block;
        height: 370px;
    }

    .photographer-image {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        margin-bottom: 15px;
    }

    .book-card p {
        color: #333;
        margin-bottom: 15px;
    }

    .actions {
        background-color: #4F709C;
        padding: 10px 10px;
        text-decoration: none;
        border-radius: 5px;
    }

    .actions a {
        color: #fff;
        text-decoration: none;
        margin-right: 10px;
    }

    .actions a:hover {
        text-decoration: none;
    }
</style>
