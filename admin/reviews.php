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
    <section class="background">
    <h4> Rating Summary</h4>
        </section>
    <div class="container">
        <!-- Rating summary -->
        
        <div class="rating-summary">
        
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
                  c.Name,
                  r.DisplayCustomerName
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
                <p>Transaction Date: <?php echo $row['TransactionDate']; ?></p>
                <?php 
                    // Display customer name based on checkbox state
                    if ($row['DisplayCustomerName'] == 1) {
                        echo $row['Name'];
                    } else {
                        echo 'Anonymous';
                    }
                    ?>
                </p>
               
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
                
        h4 {
        margin-top: 40px;
        margin-bottom: 70px;
        text-align: center;
        color: #333;
        font-weight: bold;
        font-size: 6rem;
        font-family: 'Satisfy';
    }

body {
        background-image: url('../uploads/cover.jpg');
        background-size: cover;
        background-attachment: fixed;
        height: 100vh;
    }

    .rating-summary {
        margin-top: 250px;
    background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent white background */
    border: 2px solid rgba(255,255,255, .5);
    backdrop-filter: blur(10px); /* Apply a blur effect behind the container */
    padding: 30px 40px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.15);
    color: black;
        border-radius: 8px;
        width: 80%; /* Adjust width as needed */
        max-width: 600px; /* Adjust max-width as needed */
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1; /* Ensure rating summary is on top of background */
    }

    .rating-summary h4 {
        text-align: center;
        font-size: 4rem;
        color: black;
        font-family: 'Satisfy';

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
            background-color: #2D4356; /* Gold color for 5 stars */
        }

        .bar:nth-child(2) {
            background-color: #435B66; /* Orange color for 4 stars */
        }

        .bar:nth-child(3) {
            background-color: #A76F6F; /* Tomato color for 3 stars */
        }

        .bar:nth-child(4) {
            background-color: #EAB2A0; /* Royal Blue color for 2 stars */
        }

        .bar:nth-child(5) {
            background-color: #D8C4B6; /* Chartreuse color for 1 star */
        }


        .container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between; /* Distribute items evenly */
    margin-top: 300px;
    margin-left: 400px;
    
}

.photographer-review {
    width: calc(40% ); /* Adjust width to fit three columns with some spacing */
    margin-bottom: 20px; /* Adjust as needed */
    background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent white background */
    border: 2px solid rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(10px); /* Apply a blur effect behind the container */
    padding: 30px 40px;
    color: #333;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.15);
    transform: translate(-50%, -50%);
    display: flex;
    flex-wrap: wrap;
    animation: fadeInUp 1s ease-out; /* Add fade-in animation */

}

            .photographer-image {
                flex: 0 0 100px;
                margin-right: 20px;
            }

            .photographer-image img {
                width: 100%;
                border-radius: 50%;
            }

            .review-details {
                flex: 1;
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