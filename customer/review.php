<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Photographer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <h2>Review Photographer</h2>
    <div class="container">
        <form method="post" action="storereview.php">
            <div class="form-group">
                <label for="rating">Rating:</label>
                <select class="form-control" id="rating" name="rating">
                    <option value="5">&#9733; &#9733; &#9733; &#9733; &#9733;</option> <!-- 5 stars -->
                    <option value="4">&#9733; &#9733; &#9733; &#9733; &#9734;</option> <!-- 4 stars -->
                    <option value="3">&#9733; &#9733; &#9733; &#9734; &#9734;</option> <!-- 3 stars -->
                    <option value="2">&#9733; &#9733; &#9734; &#9734; &#9734;</option> <!-- 2 stars -->
                    <option value="1">&#9733; &#9734; &#9734; &#9734; &#9734;</option> <!-- 1 star -->
                </select>
            </div>
            <div class="form-group">
                <label for="comments">Comments:</label>
                <textarea class="form-control" id="comments" name="comments" rows="5"></textarea>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="Name" name="Name" value="1">
                <label class="form-check-label" for="Name">Display my name with the review</label>
            </div>

            <input type="hidden" name="CustomerID" value="<?php echo isset($_SESSION['CustomerID']) ? $_SESSION['CustomerID'] : ''; ?>">
            <input type="hidden" name="TransactionID" value="<?php echo isset($_POST['TransactionID']) ? $_POST['TransactionID'] : ''; ?>">
            <input type="hidden" name="PhotographerID" value="<?php echo isset($_POST['PhotographerID']) ? $_POST['PhotographerID'] : ''; ?>">
            <button type="submit" name="review" class="btn btn-primary">Submit Review</button>
        </form>
    </div>
</body>
</html>


<style>
        body {
            background-image: url('../uploads/cover.jpg');  /* Set the path to your cover image */
            background-size: cover;
            background-position: center bottom; /* Lower the background image */

        }
        .container {
            max-width: 500px; /* Set maximum width for better readability */
            margin: auto; /* Center the container horizontally */
            padding: 20px; /* Add some padding */
            background-color: #fff; /* White background */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add shadow for depth */
            margin-top: 50px; /* Add some top margin for spacing */
        }
        h2 {
            margin-top: 30px;
            text-align: center;
            font-size: 4rem;
            color: #fff;
            font-family: 'Satisfy';
        }
        label {
            font-weight: bold; /* Make labels bold */
            font-family: 'serif';
        }
        textarea {
            resize: vertical; /* Allow vertical resizing of textarea */
        }
        .form-check-input {
            margin-top: 0.3rem; /* Adjust checkbox alignment */
        }
        .btn-primary {
            width: 100%; /* Make the button full width */
        }
    </style>

<?php include("../include/footer.php"); ?>