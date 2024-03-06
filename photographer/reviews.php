<?php
// Start session
session_start();

// Include database connection code here
include("../include/config.php");
include("../photographer/header.php");
?>


<body>
<h2>Reviews</h2>
    <div class="container">

        <div class="review-list">
            <?php
            // Fetch reviews for the logged-in photographer
            $photographerID = isset($_SESSION['PhotographerID']) ? $_SESSION['PhotographerID'] : null;
            if ($photographerID) {
                // Prepare the SQL statement
                $query = "SELECT r.*, c.Name AS CustomerName, c.Email FROM review r LEFT JOIN customers c ON r.CustomerID = c.CustomerID WHERE PhotographerID = ?";
                $stmt = mysqli_prepare($conn, $query);

                // Bind the parameter
                mysqli_stmt_bind_param($stmt, "i", $photographerID);

                // Execute the statement
                mysqli_stmt_execute($stmt);

                // Get the result
                $result = mysqli_stmt_get_result($stmt);

                // Check if reviews are found
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $customerName = $row['DisplayCustomerName'] ? $row['CustomerName'] : 'Anonymous Customer';
                        $rating = $row['Rate'];
                        $comment = $row['Comment'];

                        // Convert rating to star icons
                        $stars = '';
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $rating) {
                                $stars .= '<i class="fas fa-star"></i>';
                            } else {
                                $stars .= '<i class="far fa-star"></i>';
                            }
                        }

                        // Display review
                        echo "<li><strong>{$customerName}</strong> - Rating: {$stars}</p><p>{$comment}</p></li>";
                    }
                } else {
                    echo "<p>No reviews found.</p>";
                }
            } else {
                echo "<p>Photographer not logged in.</p>";
            }
            ?>
        </div>
    </div>
</body>


    <style>
    body, h1, h2, h3, h4, h5, h6, p, ul, ol, li, figure, figcaption, blockquote, dl, dd, dt {
                margin: 0;
                padding: 0;
                font-family: 'serif';
            }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
        margin-top: 30px;
        text-align: center;
        color: #333;
        font-weight: bold;
        font-size: 3rem;
        font-family: 'Satisfy';
    }

        .review-list {
            margin-top: 20px;
            padding: 0;
            list-style-type: none;
        }

        .review-list li {
            background-color: #f9f9f9;
            border-radius: 5px;
            margin-bottom: 10px;
            padding: 10px;
        }

        .review-list li strong {
            color: #333;
        }

        .review-list li p {
            margin: 5px 0 0;
        }
    </style>
    
<?php include("../include/footer.php"); ?>
