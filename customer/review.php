

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Photographer</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Review Photographer</h2>
        <form method="post" action="storereview.php">
            <div class="form-group">
                <label for="rating">Rating:</label>
                <select class="form-control" id="rating" name="rating">
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </select>
            </div>
            <div class="form-group">
                <label for="comments">Comments:</label>
                <textarea class="form-control" id="comments" name="comments" rows="5"></textarea>
            </div>
            <input type="hidden" name="TransactionID" value="<?php echo isset($_POST['TransactionID']) ? $_POST['TransactionID'] : ''; ?>">
            <input type="hidden" name="PhotographerID" value="<?php echo isset($_POST['PhotographerID']) ? $_POST['PhotographerID'] : ''; ?>">
            <button type="submit" name="review" class="btn btn-primary">Submit Review</button>
        </form>
    </div>
</body>
</html>

