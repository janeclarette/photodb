<?php
session_start(); // Start the session
include("../include/config.php"); 
include("../customer/header.php"); 

if (isset($_GET['photographer_id'])) {
    $photographerID = $_GET['photographer_id'];

    $getAlbumsSql = "SELECT * FROM works WHERE PhotographerID = $photographerID";
    $albumsResult = $conn->query($getAlbumsSql);

    if ($albumsResult) {
?>
        <style>
             .background {
            background-image: url('../uploads/cover.jpg');  /* Set the path to your cover image */
            background-size: cover;
            background-position: center top; /* Lower the background image */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 40%;
            z-index: -1; /* Push the background behind other content */
            
        }

        body {
            background-color: #fff;
        }
            .services {
                margin-top: 300px;
                height: 2000px;
                margin-bottom: 100px;
            }

            h2 {
            text-align: center;
            font-size: 5rem;
            font-family: 'Satisfy';
            color: #333;
            margin-top: 10px;
            margin-bottom: 20px;
        }
            .album-container {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
                gap: 20px;
                margin: 10px;
                height: 400px;
            }

            .album-card {
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                transition: box-shadow 0.3s ease-in-out;
                position: relative;
                margin-right: 90px;
                margin-left: 50px;
                margin-bottom: 50px;
                height: 700px;
            }

            .album-card:hover {
                box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            }

            .album-details {
                text-align: center;
                margin-top: 20px;
            }

            .album-details h3 {
                font-size: 2rem;
                font-family: 'Satisfy';
                color: #333;
                margin-bottom: 10px;
            }

            .album-details p {
                font-size: 1.5rem;
                font-family: serif;
                color: #333;
                margin-bottom: 10px;
            }

            .photo-container {
                max-height: 500px;
                overflow-y: auto;
                margin-bottom: 20px;
            }

            .photo-container img {
                display: block;
                margin: 0 auto;
                max-width: 100%;
                height: auto;
                margin-bottom: 40px;
            }

            .work-id-container {
                color: #fff;
                padding: 10px;
                border-bottom-left-radius: 15px;
                border-bottom-right-radius: 15px;
                text-align: center;
            }
        </style>
    </head>

    <div class = "background">
    <body>        </div>
        <h2>Photographer's Albums</h2>
        <div class = "services">


        <div class="album-container">
            <?php
            while ($album = $albumsResult->fetch_assoc()) {
                echo '<div class="album-card">';

                $photos = explode(',', $album['Photos']);
                if (!empty($photos)) {
                    echo '<div class="photo-container">';
                    foreach ($photos as $index => $photo) {
                        echo '<img src="' . $photo . '" alt="Photo">';
                    }
                    echo '</div>';
                }

                echo '<div class="album-details">';
                echo '<h3> Album Title: ' . $album['Album'] . '</h3>';
                echo '<p>Description: ' . $album['Description'] . '</p>';

                $serviceTypeID = $album['ServiceTypeID'];

                if (!empty($serviceTypeID)) {
                    $getServiceTypeSql = "SELECT TypeName FROM ServiceTypes WHERE ServiceTypeID = $serviceTypeID";
                    $serviceTypeResult = $conn->query($getServiceTypeSql);

                    if ($serviceTypeResult && $serviceTypeResult->num_rows > 0) {
                        $serviceType = $serviceTypeResult->fetch_assoc();
                        echo '<div class="work-id-container">';
                        echo '<p>Service Type: ' . $serviceType['TypeName'] . '</p>';
                        echo '</div>';
                    } else {
                        echo '<p>Unknown Service Type</p>';
                    }
                } else {
                    echo '<p>Service Type ID is empty</p>';
                }

                echo '</div>'; // Close album-details div
                echo '</div>'; // Close album-card div
            }
            ?>
                    </div>
        </div>

    </body>
</html>
<?php
    } else {
        echo "Error retrieving albums: " . $conn->error;
    }
} else {
    echo "Photographer ID not specified.";
}

$conn->close();
?>

<?php include("../include/footer.php"); ?>
