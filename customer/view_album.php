<?php

include("../include/config.php"); 
?>
    <title>Customer Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <body>
    <header class="navbar">
        <div class="logo">
            <a href="#"><img src="../uploads/C.png" alt="Logo"></a>
        </div>
        <div class="search">
            <input type="text" placeholder="Search">
            <button type="submit">Search</button>
        </div>
        <div class="profile">
    <div class="sign-in">
                <a href="/photodb/customer/profile.php"> <i class="fa-regular fa-user"></i></a>
    </div>
    <div class="message">
        <a href="/photodb/customer/message.php"><i class="fa-regular fa-message"></i></a>
    </div>
    <div class="logout">
        <a href="/photodb/admin/logout.php"><i class="fas fa-sign-in-alt"></i></a>
    </div>

</div>

    
    </div>

    </header>
    <nav class="sub-navbar">
        <ul>
            <li><a href="#">Home</a></li>       
            <li><a href="photographer.php">Photographers</a></li>


            <li class="dropdown">
    <a href="#">Services</a>
    <div class="dropdown-content">
        <?php
        $serviceTypesSql = "SELECT * FROM servicetypes";
        $serviceTypesResult = $conn->query($serviceTypesSql);

        while ($serviceTypeRow = $serviceTypesResult->fetch_assoc()) {
            $typeName = $serviceTypeRow['TypeName'];
            $typeParam = urlencode(strtolower(str_replace(
                array('Wedding Photography', 'Portrait Photography', 'Event Coverage', 'Commercial Photography', 'Family Photography', 'Fashion Photography', 'Newborn Photography', 'Landscape Photography', 'Food Photography', 'Sports Photography'),
                array('wedding', 'portrait', 'event', 'commercial', 'family', 'fashion', 'newborn', 'landscape', 'food', 'sports'),
                $typeName
            ))); 

            echo "<a href='$typeParam.php'>$typeName</a>";
        }
        ?>
    </div>
</li>
            <li><a href="#">Reviews</a></li>
            <li><a href="#">Photo Gallery</a></li>
            <li><a href="price.php">Pricing</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact Us</a></li>
        </ul>
    </nav>
    

    </div>
</body>
</html>

  <style>

        body, h1, h2, h3, h4, h5, h6, p, ul, ol, li, figure, figcaption, blockquote, dl, dd, dt {
            margin: 0;
            padding: 0;
        }

        .navbar {
 
            background-color: #213555;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo img {
            margin-left: 40px;
            height: 80px; 
            width: auto; 
        }

        .navbar .search input[type="text"] {
            padding: 10px;
            border: none;
            border-radius: 10px;
            margin-right: 10px;
            width: 300px;
        }

        .navbar .search button {
            padding: 5px 10px;
            background-color: #4F709C;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
        }

        .navbar .profile a {
            color: #fff;
            text-decoration: none;
        }

        .sub-navbar {
            background-color: #4F709C;
            color: #fff;
            padding: 10px;
        }

        .sub-navbar ul {
            list-style-type: none;
            display: flex;
            justify-content: space-around;
        }

        .sub-navbar ul li {
            margin-right: 10px;
        }

        .sub-navbar ul li a {
            color: #fff;
            text-decoration: none;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #9BABB8;
            min-width: 160px;
            z-index: 1=;
        }

        .dropdown-content a {
            color: #fff;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .profile {
            display: flex;
            align-items: center;
        }

        .sign-in,
        .logout {
            margin-right: 40px; 
        }

        .sign-in .dropdown,
        .logout a {
            padding: 25px;
        }

        .message{
            margin-right: 10px;
        }

      
        .work-id-container {
            background-color: #4F709C;
            color: #E9E4D4;
            padding: 10px;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
            width: 380px;
            text-align: center;
            font color:white;
            
        }

        .photographer-container {
        width: calc(33.33% - 40px); 
        margin: 20px;
        box-sizing: border-box; 
    }
    </style>
<?php
include("../include/config.php");

if (isset($_GET['photographer_id'])) {
    $photographerID = $_GET['photographer_id'];

    $getAlbumsSql = "SELECT * FROM works WHERE PhotographerID = $photographerID";
    $albumsResult = $conn->query($getAlbumsSql);

    if ($albumsResult) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Photographer's Albums</title>
            <style>
                body {
                    font-family: 'Arial', sans-serif;
                }

                .album-container {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    margin: 20px;
                }

                .photographer-container {
                    margin: 50px;
                    width: 500px;
                }

                .album-card {
                    width: 100%; 
                    text-align: center;
                    background-color: #F5EFE7;
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
