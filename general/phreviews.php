<?php
session_start(); // Start the session
// Include necessary files and establish a database connection
include("../include/config.php");
include("../general/header.php");

// Fetch photographer reviews from the database along with transaction date for reviewed ones
$query = "SELECT p.Name AS PhotographerName, 
                  p.img_photographer AS PhotographerImage, 
                  r.Rate AS Rating, 
                  r.Comment AS Comment,
                  t.TransactionDate AS TransactionDate,
                  c.Email,
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
                <p>Transaction Date: <?php echo $row['TransactionDate']; ?></p>
                <p>
                    <?php 
                    // Display customer name or email based on checkbox state
                    if ($row['DisplayCustomerName'] == 1) {
                        echo $row['Email'];
                    } else {
                        // Get the length of the email
                        $emailLength = strlen($row['Email']);
                        // Print asterisks equivalent to the length of the email
                        echo str_repeat('*', $emailLength - 10) . substr($row['Email'], -10);
                    }
                    ?>
                </p>

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

        .container {
            max-width: 1000px;
            margin: 20px auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
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