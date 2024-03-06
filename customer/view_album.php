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


                .album-container {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    margin: 10px;
                }

                .photographer-container {
                    margin: 40px;
                    width: 500px;
                }

                .album-card {
                    width: 100%; 
                    text-align: center;
                    background-color: #fff;
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                    transition: box-shadow 0.3s ease-in-out;
                    position: relative;
                }

                .album-card:hover {
                    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
                }

                .slideshow-container {
                    max-width: 100%;
                    margin: auto;
                    overflow: hidden;
                    position: relative;
                    height: 400px; 
                }

                .slideshow-image {
                    width: 100%;
                    height: 100%;
                    display: none;
                    position: absolute;
                    transition: opacity 0.5s ease;
                }

                .slideshow-image.active {
                    display: block;
                    opacity: 1;
                }

                .arrow {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    font-size: 24px;
                    color: #213555;
                    cursor: pointer;
                }

                .arrow.left {
                    left: 10px;
                }

                .arrow.right {
                    right: 10px;
                }



                h2 {
            text-align: center;
            font-size: 4rem;
            font-family: 'Satisfy';
            color: #333;
            margin-top: 40px;
            margin-bottom: 20px;
        }
        h3 {
            text-align: center;
            font-size: 2rem;
            font-family: 'Satisfy';
            color: #333;
            margin-top: 10px;
            margin-bottom: 10px;
        }
         p {
            text-align: center;
            font-size: 1.5rem;
            font-family:  serif;
            color: #333;
            margin-bottom: 10px;
        }
        .work-id-container {
            background-color: #213555;
            color: #fff;
            padding: 10px;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
            width: 550px;
            text-align: center;
            
        }
            </style>
        </head>
        <body>
        <h2>Photographer's Albums</h2>
        <div class="album-container">
        <?php
        while ($album = $albumsResult->fetch_assoc()) {
            echo '<div class="photographer-container">';

            $photos = explode(',', $album['Photos']);
            if (!empty($photos)) {
                echo '<div class="album-card">'; 
                echo '<div class="slideshow-container">';
                foreach ($photos as $index => $photo) {
                    echo '<img class="slideshow-image';
                    echo ($index === 0) ? ' active' : '';
                    echo '" src="' . $photo . '" alt="Photo">';
                }
                echo '<div class="arrow left" onclick="changeSlide(this.parentNode, -1)">&#9664;</div>';
                echo '<div class="arrow right" onclick="changeSlide(this.parentNode, 1)">&#9654;</div>';
                echo '</div>';
                echo '</div>';  
            }

            echo '<div class="album-card">';
            echo '<h3> Album Title: ' . $album['Album'] . '</h3>';
            echo '<p>Description: ' . $album['Description'] . '</p>';
            echo '</div>';

            $serviceTypeID = $album['ServiceTypeID'];

            if (!empty($serviceTypeID)) {
                $getServiceTypeSql = "SELECT TypeName FROM ServiceTypes WHERE ServiceTypeID = $serviceTypeID";
                $serviceTypeResult = $conn->query($getServiceTypeSql);

                if ($serviceTypeResult && $serviceTypeResult->num_rows > 0) {
                    $serviceType = $serviceTypeResult->fetch_assoc();
                    echo '<div class="work-id-container">';
    echo '<p style="color: white;">Service Type: ' . $serviceType['TypeName'] . '</p>';
    echo '</div>';
                } else {
                    echo '<p>Unknown Service Type</p>';
                }
            } else {
                echo '<p>Service Type ID is empty</p>';
            }

            echo '</div>';
        }
        ?>

            <script>
                var currentIndex = 0;

                function changeSlide(slideshow, direction) {
                    var slides = slideshow.querySelectorAll('.slideshow-image');

                    currentIndex = (currentIndex + direction + slides.length) % slides.length;

                    slides.forEach(function (slide, i) {
                        slide.style.display = i === currentIndex ? 'block' : 'none';
                    });
                }
            </script>
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