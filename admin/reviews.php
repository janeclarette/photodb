<?php
// Include necessary files and establish a database connection
include("../include/config.php");
include("../admin/adminheader.php");

// Fetch photographer reviews from the database along with transaction date for reviewed ones
$query = "SELECT p.Name AS PhotographerName, 
                  p.img_photographer AS PhotographerImage, 
                  r.Rate AS Rating, 
                  r.Comment AS Comment,
                  t.TransactionDate AS TransactionDate,
                  IF(r.CustomerID IS NOT NULL, c.Name, SHA1(r.CustomerID)) AS CustomerName,
                  r.CustomerID
          FROM photographers p
          LEFT JOIN review r ON p.PhotographerID = r.PhotographerID
          LEFT JOIN transactions t ON r.TransactionID = t.TransactionID
          LEFT JOIN customers c ON r.CustomerID = c.CustomerID
          WHERE r.Rate IS NOT NULL AND r.Comment IS NOT NULL";

$result = mysqli_query($conn, $query);

// Initialize variables for rating summary
$totalRatings = 0;
$totalStars = 0;
$ratingsDistribution = array_fill(0, 5, 0); // Initialize array to store counts for each star rating

// Check for query execution success
if ($result) {
    // Calculate rating summary
    while ($row = mysqli_fetch_assoc($result)) {
        $rating = $row['Rating'];
        $totalStars += $rating;
        $totalRatings++;
        $ratingsDistribution[$rating - 1]++; // Increment count for respective star rating
    }
    
    // Calculate overall rating
    $overallRating = $totalRatings > 0 ? round($totalStars / $totalRatings, 1) : 0;

    ?>

    <body>
    <div class="container">
        <!-- Rating summary -->
        <div class="rating-summary">
            <h2>Rating Summary</h2>
            <p>Overall Rating: <?php echo $overallRating; ?></p>
            <!-- Bar chart for ratings distribution -->
            <div class="ratings-chart">
                <?php
                for ($i = 5; $i >= 1; $i--) {
                    $count = $ratingsDistribution[$i - 1];
                    echo "<div class='bar' style='width: " . ($count / $totalRatings * 100) . "%;'>$i star: $count</div>";
                }
                ?>
            </div>
        </div>
        <!-- Photographer reviews -->
        <?php
        mysqli_data_seek($result, 0); // Reset result pointer to start from the beginning
        while ($row = mysqli_fetch_assoc($result)) {
            // Display individual reviews
            // Code for displaying reviews (same as before)
        }
        ?>
    </div>
    </body>
    </html>
    <?php
} else {
    // Handle query error
    echo "Error: " . mysqli_error($conn);
}


// Fetch photographer reviews from the database along with transaction date for reviewed ones
$query = "SELECT p.Name AS PhotographerName, 
                  p.img_photographer AS PhotographerImage, 
                  r.Rate AS Rating, 
                  r.Comment AS Comment,
                  t.TransactionDate AS TransactionDate,
                  IF(r.CustomerID IS NOT NULL, c.Name, SHA1(r.CustomerID)) AS CustomerName,
                  r.CustomerID
          FROM photographers p
          LEFT JOIN review r ON p.PhotographerID = r.PhotographerID
          LEFT JOIN transactions t ON r.TransactionID = t.TransactionID
          LEFT JOIN customers c ON r.CustomerID = c.CustomerID
          WHERE r.Rate IS NOT NULL AND r.Comment IS NOT NULL";

$result = mysqli_query($conn, $query);

// Check for query execution success
if ($result) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Photographer Reviews</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <style>
            /* CSS styles */
        </style>
    </head>
    <body>
    <div class="container">
    <?php
    // Loop through each review
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="photographer-review">
            <div class="photographer-image">
                <img src="<?php echo $row['PhotographerImage']; ?>" alt="Photographer Image">
            </div>
            <div class="review-details">
                <h3><?php echo $row['PhotographerName']; ?></h3>
                <p>Transaction Date: <?php echo $row['TransactionDate']; ?></p>
                <p>Customer Name: <?php echo isset($_POST['Name']) && $_POST['Name'] == 1 ? $row['CustomerName'] : 'Anonymous'; ?></p>

                <div class="rating">
                    <?php
                    // Display stars based on rating
                    $rating = $row['Rating'];
                    for ($i = 1; $i <= 5; $i++) {
                        if ($rating >= $i) {
                            echo '<i class="fas fa-star"></i>';
                        } elseif ($rating > ($i - 1) && $rating < $i) {
                            echo '<i class="fas fa-star-half-alt"></i>';
                        } else {
                            echo '<i class="far fa-star"></i>';
                        }
                    }
                    ?>
                </div>
                <div class="comments">
                    <p><?php echo $row['Comment']; ?></p>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>

    </body>
    </html>
    <?php
} else {
    // Handle query error
    echo "Error: " . mysqli_error($conn);
}
?>



        <style>
                body {
        background-color: #E0F4FF;
    }
        .container {
            width: 1000px;
            margin: 20px auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }


        .rating-summary {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            width: 1000px;
        }

        .rating-summary h2 {
            margin-top: 0;
        }

        .rating-summary p {
            margin: 5px 0;
        }

        .ratings-chart {
            margin-top: 20px;
        }

        .bar {
            background-color: #4F709C;
            color: #fff;
            padding: 15px;
            margin-bottom: 5px;
            border-radius: 5px;
        }

        /* Style each bar based on its star rating */
        .bar:nth-child(1) {
            background-color: #FFD700; /* Gold color for 5 stars */
        }

        .bar:nth-child(2) {
            background-color: #FFA500; /* Orange color for 4 stars */
        }

        .bar:nth-child(3) {
            background-color: #FF6347; /* Tomato color for 3 stars */
        }

        .bar:nth-child(4) {
            background-color: #4169E1; /* Royal Blue color for 2 stars */
        }

        .bar:nth-child(5) {
            background-color: #7FFF00; /* Chartreuse color for 1 star */
        }


        .photographer-review {
            width: 45%;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ccc;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex; /* Added */
            justify-content: center; /* Added */
            align-items: center; /* Added */
            flex-direction: column; /* Added */
            text-align: center; /* Added */
        }

        .photographer-review:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .photographer-image {
            width: 40%;
            margin-right: 5%;
            padding: 20px;
        }

        .photographer-image img {
            width: 100%;
            border-radius: 50%;
            display: block; /* Added */
            margin: 0 auto; /* Added */
        }

        .review-details {
            width: 100%; /* Changed from 55% to 100% */
            padding: 20px;
        }

        .photographer-review h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #333;
        }

        .rating {
            margin-bottom: 10px;
            color: #ffac00; /* Star color */
        }

        .comments p {
            margin: 0;
        }

        .fas.fa-star {
            color: #ffac00; /* Filled star color */
        }
    </style>