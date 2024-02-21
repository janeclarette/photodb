<?php
session_start();
include("../include/config.php");

if (isset($_SESSION['PhotographerID'])) {
    $photographerID = $_SESSION['PhotographerID'];

    $sql = "SELECT DISTINCT Album, Description FROM works WHERE PhotographerID = $photographerID";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $albums = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $albums = [];
    }
} else {
    header("Location: /your-login-page.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["images"]) && !empty($_FILES["images"]["name"][0])) {
        $uploadDirectory = "../uploads/";
        $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif'];

        $imagePaths = [];
        if (isset($_SESSION['PhotographerID'])) {
            $photographerID = $_SESSION['PhotographerID'];

            $album = $conn->real_escape_string($_POST['album']);
            $description = $conn->real_escape_string($_POST['description']);

            $allImagePaths = []; 

            foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
                $fileName = $_FILES["images"]["name"][$key];
                $fileSize = $_FILES["images"]["size"][$key];
                $fileType = $_FILES["images"]["type"][$key];
                $fileTmpName = $_FILES["images"]["tmp_name"][$key];
                $fileError = $_FILES["images"]["error"][$key];
                if ($fileError === 0) {
                    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                    if (in_array(strtolower($fileExtension), $allowedFileTypes)) {
                        $destination = $uploadDirectory . $fileName;
                        move_uploaded_file($fileTmpName, $destination);
                        $imagePaths[] = $destination; 
                    } else {
                        echo "File '$fileName' has an invalid file type. Allowed types: " . implode(", ", $allowedFileTypes) . ".<br>";
                    }
                } else {
                    echo "Error uploading file '$fileName'.<br>";
                }
            }

            $allImagePathsString = implode(",", $imagePaths);

            $serviceType = $_POST['serviceType'];
            $sql = "INSERT INTO works (Photos, PhotographerID, Album, Description, ServiceTypeID) 
                    VALUES ('$allImagePathsString', '$photographerID', '$album', '$description', '$serviceType')";

            $result = $conn->query($sql);
          
            if ($result) {
                echo '<script>';
                echo 'alert("File uploaded successfully");';
                echo 'window.location.href = "work_create.php";';
                echo '</script>';
            } else {
                echo "Error inserting images into the database.<br>";
            }
        } else {
            echo "Error: Photographer ID not found in session.<br>";
        }
    } else {
        echo "Please select at least one file to upload.<br>";
    }
}
?>
  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photographer Page</title>
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
                <a href="/photodb/photographer/profile.php"> <i class="fa-regular fa-user"></i></a>
    </div>
    <div class="message">
        <a href="/photodb/photographer/message.php"><i class="fa-regular fa-message"></i></a>
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
            <li><a href="work_create.php">Portfolio</a></li>
            <li><a href="schedule.php">Schedule</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="package.php">Package</a></li>
            <li><a href="place.php">Place</a></li>
            <li><a href="#">Reviews</a></li>
        </ul>
    </nav>
     <style>

.slideshow-container {
        position: relative;
        max-width: 100%;
        margin: auto;
        overflow: hidden; 
    }

    .slideshow-image {
        width: 100%;
        height: 95%%;
        display: none;
        transition: opacity 0.5s ease; 
    }

    .slideshow-image.active {
        display: block;
        opacity: 1; 
    }

    .albums-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: space-evenly;
        margin-top: 20px;
    }

   .album-card {
    flex: 1 1 calc(25% - 20px);
    max-width: calc(25% - 20px);
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.3s ease;
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    text-align: center;
    height: 465px;
}

.album-card img {
    max-width: 100%;
    height: auto%;
}


    .album-card:hover {
        transform: scale(1.05);
    }

    .album-details {
        padding: 10px;
        flex-grow: 1;
        
    }

    .album-details h3 {
        color: #213555;
        font-size: 1.5rem;
        margin-bottom: 10px;
    }

    .album-details p {
        color: #666;
        font-size: 1rem;
        margin-bottom: 15px;
    }

    .view-more-btn {
        padding: 8px 12px;
        background-color: #4F709C;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    .view-more-btn:hover {
        background-color: #213555;
    }
        body {
            background-color: #F3EEEA;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #213555;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .logo img {
            margin-left: 40px;
            height: 60px;
            width: auto;
        }

        .search input[type="text"] {
            padding: 10px;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
            width: 200px;
        }

        .search button {
            padding: 8px 12px;
            background-color: #4F709C;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
        }

        .profile a {
            color: #fff;
            text-decoration: none;
            margin-right: 10px;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .profile a:hover {
            color: #4F709C;
        }

        .sub-navbar {
            background-color: #4F709C;
            color: #fff;
            padding: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .sub-navbar ul li a:hover {
            color: #F3EEEA;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            
        }

        h2 {
            text-align: center;
            color: #213555; 
            font-size: 2rem;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            font-size: 1.2rem;
            margin-top: 10px;
            text-align: center;
        }

        input[type="file"] {
            margin-top: 5px;
        }

        input,
        textarea {
            margin-top: 10px;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            text-align: center; 
        }

        input[type="submit"] {
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #4F709C;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #213555;
        }
       
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
        .logout{
            margin-right: 40px;
        }

        .sign-in .dropdown,
        .logout a {
            padding: 25px;
        }

        .message{
            margin-right: 10px; 
        }
        </style>
</head>
<body>
<div class="container">
    <h2>Upload Images</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="images">Select Images (Multiple):</label>
        <input type="file" name="images[]" id="images" multiple accept="image/*">
        <br>
        <label for="album">Album:</label>
        <input type="text" name="album" id="album" required>
        <br>
        <label for="serviceType">Service Type
        <select name="serviceType" id="serviceType" required>
        <option value="" disabled selected>Select your Service Type</option>
            <?php
            $sql = "SELECT * FROM ServiceTypes";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['ServiceTypeID'] . "'>" . $row['TypeName'] . "</option>";
            }
            ?>
        </select>
        </label>
        <br>
        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4" required></textarea>
        <br>
        <input type="submit" value="Upload">
    </form>
</div>
<div class="albums-container">
    <?php foreach ($albums as $album) : ?>
        <div class="album-card" data-album="<?= $album['Album']; ?>">
            <?php
            $albumID = $album['Album']; 
            $sqlImages = "SELECT Photos FROM works WHERE PhotographerID = $photographerID AND Album = '$albumID'";
            $resultImages = $conn->query($sqlImages);

            if ($resultImages && $resultImages->num_rows > 0) {
                $images = $resultImages->fetch_all(MYSQLI_ASSOC);
            } else {
                $images = [];
            }
            $sqlServiceType = "SELECT st.TypeName FROM works w
                                JOIN ServiceTypes st ON w.ServiceTypeID = st.ServiceTypeID
                                WHERE w.PhotographerID = $photographerID AND w.Album = '$albumID'";
            $resultServiceType = $conn->query($sqlServiceType);

            if ($resultServiceType) {
                if ($resultServiceType->num_rows > 0) {
                    $serviceType = $resultServiceType->fetch_assoc();
                } else {
                    $serviceType = ['TypeName' => 'No Service Type'];
                }
            } else {
                echo "Error in Service Type query: " . $conn->error;
                $serviceType = ['TypeName' => ''];
            }
            ?>

            <div class="slideshow-container">
                <?php foreach ($images as $key => $image) : ?>
                    <?php $imagePaths = explode(',', $image['Photos']); ?>
                    <?php foreach ($imagePaths as $imagePath): ?>
                        <img src="<?= $imagePath ?>" alt="Album Image" class="slideshow-image">
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>

            <h3>Album Title: <?= $album['Album']; ?></h3>
            <p>Service Type: <?= $serviceType['TypeName']; ?></p>
            <p>Description: <?= $album['Description']; ?></p>
            <button class="view-more-btn">View More</button>
        </div>
    <?php endforeach; ?>
</div>



<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.album-card').forEach(function (albumCard) {
            const slideshowContainer = albumCard.querySelector('.slideshow-container');
            const slideshowImages = albumCard.querySelectorAll('.slideshow-image');
            let currentImageIndex = 0;
            function showNextImage() {
                slideshowImages[currentImageIndex].style.display = 'none';
                currentImageIndex = (currentImageIndex + 1) % slideshowImages.length;
                slideshowImages[currentImageIndex].style.display = 'block';
            }

            if (slideshowImages.length > 0) {
                slideshowImages[currentImageIndex].style.display = 'block';

                setInterval(showNextImage, 8000);
            }
        });
    });
</script>

</body>
</html>
