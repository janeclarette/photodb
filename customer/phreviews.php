<?php
session_start(); // Start the session
// Include necessary files and establish a database connection
include("../include/config.php");
include("../customer/header.php");

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

    </head>
    <body>

    <section class="background">
        <h2>Reviews<h2>
</section>

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
    background: linear-gradient(to bottom, #CEE6F3 ,#4F709C); /* Dark blue to light blue gradient */
    background-size: cover; /* Cover the entire background without distortion */
    background-position: center; /* Center the background image */
    background-repeat: no-repeat; /* Prevent the background from repeating */
}

        .background {
            background-image: url('../uploads/cover.jpg');  /* Set the path to your cover image */
            background-size: cover;
            background-position: center bottom; /* Lower the background image */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 60%;
            z-index: -1; /* Push the background behind other content */
        }

        h2 {
            margin-top: 160px;
            text-align: center;
            font-size: 6rem;
            color: #fff;
            font-family: 'Satisfy';
        }
        h5 {
            font-size: 2rem;
            color: #333;
            font-family: 'Satisfy';
        }
        

        .container {
            max-width: 1000px;
            margin-top: 400px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .photographer-review {
            width: 30%; /* Set width to 30% for three columns */
            padding-bottom: 20px;
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
            margin-bottom: 20px;
        }

        .photographer-review:hover {
    transform: scale(1.1); /* Scale up the element by 10% on hover */
    transition: transform 0.3s ease; /* Smooth transition over 0.3 seconds */
}
        .photographer-review:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .photographer-image {
            width: 50%;
            margin-right: 5%;
            padding: 20px;
        }

        .photographer-image img {
            width: 100%;
            border-radius: 50%;
            display: block;
            margin: 0 auto;
        }

        .review-details {
            width: 100%;
            padding: 20px;
        }

        .photographer-review h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #333;
        }

        .rating {
            margin-bottom: 10px;
            color: #ffac00;
        }

        .comments p {
            margin: 0;
            font-style: italic;
        }

        p {
            margin-bottom: 20px;
        }
        .fas.fa-star {
            color: #ffac00;
            margin-bottom: 20px;
        }

    </style>