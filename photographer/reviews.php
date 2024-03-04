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
                $query = "SELECT * FROM review WHERE PhotographerID = ?";
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
                        $customerID = $row['CustomerID'];
                        $rate = $row['Rate'];
                        $comment = $row['Comment'];

                        // Fetch customer name
                        $customerQuery = "SELECT name FROM customers WHERE CustomerID = ?";
                        $stmt2 = mysqli_prepare($conn, $customerQuery);
                        mysqli_stmt_bind_param($stmt2, "i", $customerID);
                        mysqli_stmt_execute($stmt2);
                        $customerResult = mysqli_stmt_get_result($stmt2);
                        $customerName = ($row2 = mysqli_fetch_assoc($customerResult)) ? $row2['name'] : 'Anonymous Customer';

                        // Display review
                        echo "<li><strong>{$customerName}</strong> - Rating: {$rate}</p><p>{$comment}</p></li>";
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