<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photographer Details</title>
    <style>
        body {
            background-image: url('../uploads/cover.jpg');
            background-size: cover;
            background-attachment: fixed;
            height: 100vh;
            font-family: 'serif';
        }

        h3, h2{ 
            font-size: 2rem;
        }
        p {
            font-size: 1.3rem;
        }
        .container {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent white background */
            border: 2px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px); 
        }

        .left-column {
            flex: 1;
            margin-right: 20px;
        }

        .right-column {
            flex: 1;
        }

        .photographer-details {
            text-align: center;
        }

        .photographer-img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: block;
        }

        .packages-list,
        .places-list {
            margin-top: 20px;
        }

        .works-list {
            margin-top: 20px;
        }

        .works-list li {
            margin-bottom: 10px;
        }

        .work-photo {
            max-width: 100px;
            height: 100px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<?php
session_start();
include("../include/config.php");
include("../admin/adminheader.php");

// Check if PhotographerID is provided via GET request
if(isset($_GET['photographer_id'])) {
    $photographer_id = $_GET['photographer_id'];

    // Fetch photographer information
    $photographer_query = "SELECT * FROM photographers WHERE PhotographerID = $photographer_id";
    $photographer_result = mysqli_query($conn, $photographer_query);

    if($photographer = mysqli_fetch_assoc($photographer_result)) {
        ?>
        <div class="container">
            <div class="left-column">
                <!-- Photographer Details -->
                <div class="photographer-details">
                    <?php
                    // Display photographer image if available
                    if (!empty($photographer['img_photographer'])) {
                        echo "<img src='../uploads/{$photographer['img_photographer']}' alt='Photographer Image' class='photographer-img'>";
                    } else {
                        echo "<p>No image available</p>";
                    }
                    ?>
                    <h2><?php echo $photographer['Name']; ?></h2>
                    <p>Phone Number: <?php echo $photographer['Phone_Number']; ?></p>
                    <p>Email: <?php echo $photographer['Email']; ?></p>
                </div>
                <!-- Photographer's Packages -->
                <?php
                $packages_query = "SELECT * FROM packages WHERE PhotographerID = $photographer_id";
                $packages_result = mysqli_query($conn, $packages_query);
                if(mysqli_num_rows($packages_result) > 0) {
                    ?>
                    <h3>Packages</h3>
                    <ul class="packages-list">
                        <?php while($package = mysqli_fetch_assoc($packages_result)) { ?>
                            <p><?php echo $package['PackageName']; ?> 
                            <br><br><strong>Price:</strong> <?php echo $package['Price']; ?></p>
                        <?php } ?>
                    </ul>
                <?php } ?>

                <!-- Photographer's Places -->
                <?php
                $places_query = "SELECT * FROM places WHERE PhotographerID = $photographer_id";
                $places_result = mysqli_query($conn, $places_query);
                if(mysqli_num_rows($places_result) > 0) {
                    ?>
                    <h3>Places</h3>
                    <ul class="places-list">
                        <?php while($place = mysqli_fetch_assoc($places_result)) { ?>
                            <p><?php echo $place['PlaceName']; ?>
                            <br><br><strong> Address: </strong><?php echo $place['Address']; ?></p>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
            <div class="right-column">
                <!-- Photographer's Works -->
                <?php
                $works_query = "SELECT * FROM works WHERE PhotographerID = $photographer_id";
                $works_result = mysqli_query($conn, $works_query);
                if(mysqli_num_rows($works_result) > 0) {
                    ?>
                    <h3>Works</h3>
                    <ul class="works-list">
                        <?php while($work = mysqli_fetch_assoc($works_result)) { ?>
                            
                                <?php
                                // Check if there's a photo associated with the work
                                if (!empty($work['Photos'])) {
                                    // Split the photos by comma and loop through each one
                                    $photos = explode(',', $work['Photos']);
                                    foreach ($photos as $photo) {
                                        echo "<img src='../uploads/{$photo}' alt='Work Photo' class='work-photo'>";
                                    }
                                } else {
                                    echo "No photo available";
                                }
                                ?>
                                <br>
                                <h3>Description:</h3><li> <p><?php echo $work['Description']; ?></p></li>
                            
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
        </div>
        <?php
    } else {
        echo "Photographer not found.";
    }
} else {
    echo "Photographer ID not provided.";
}

// Close connection
mysqli_close($conn);
?>
</body>
</html>
