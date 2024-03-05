<?php
session_start();
include("../include/config.php");
include("../photographer/header.php");

if (isset($_SESSION['PhotographerID'])) {
    $photographerID = $_SESSION['PhotographerID'];

    $sql = "SELECT WorkID, Album, Description FROM works WHERE PhotographerID = $photographerID";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $albums = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $albums = [];
    }
} else {
    header("Location: /photodb/admin/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["images"]) && !empty($_FILES["images"]["name"][0])) {
        $uploadDirectory = "../uploads/";
        $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif'];

        $imagePaths = [];
        foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
            $fileName = $_FILES["images"]["name"][$key];
            $fileError = $_FILES["images"]["error"][$key];
            if ($fileError === 0) {
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                if (in_array(strtolower($fileExtension), $allowedFileTypes)) {
                    $destination = $uploadDirectory . $fileName;
                    move_uploaded_file($_FILES["images"]["tmp_name"][$key], $destination);
                    $imagePaths[] = $destination;
                } else {
                    echo "File '$fileName' has an invalid file type. Allowed types: " . implode(", ", $allowedFileTypes) . ".<br>";
                }
            } else {
                echo "Error uploading file '$fileName'.<br>";
            }
        }

        $allImagePathsString = implode(",", $imagePaths);

        $album = $conn->real_escape_string($_POST['album']);
        $description = $conn->real_escape_string($_POST['description']);
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
        echo "Please select at least one file to upload.<br>";
    }
}
?>

<body>
    <h2>Upload Images</h2>

    <div class="container">

        <form action="" method="post" enctype="multipart/form-data">
            <label for="images">Select Images (Multiple):</label>
            <input type="file" name="images[]" id="images" multiple accept="image/*">
            <br>
            <label for="album">Album:</label>
            <input type="text" name="album" id="album" required>
            <br>
            <label for="serviceType">Service Type:</label>
            <select name="serviceType" id="serviceType" required>
                <?php
                $sql = "SELECT * FROM ServiceTypes";
                $result = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['ServiceTypeID'] . "'>" . $row['TypeName'] . "</option>";
                }
                ?>
            </select>
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
                $albumID = $album['WorkID'];
                $sqlImages = "SELECT Photos FROM works WHERE PhotographerID = $photographerID AND WorkID = $albumID";
                $resultImages = $conn->query($sqlImages);

                if ($resultImages && $resultImages->num_rows > 0) {
                    $images = $resultImages->fetch_all(MYSQLI_ASSOC);
                } else {
                    $images = [];
                }
                $sqlServiceType = "SELECT st.TypeName FROM works w
                                    JOIN ServiceTypes st ON w.ServiceTypeID = st.ServiceTypeID
                                    WHERE w.PhotographerID = $photographerID AND w.WorkID = $albumID";
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
                <button class="view-more-btn"><a href="update_portfolio.php?workID=<?php echo $album['WorkID']; ?>">View More</a></button>
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

                    setInterval(showNextImage, 2000);
                }
            });
        });
    </script>
</body>

</html>

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
    height: 500px;
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
            background-color: #E0F4FF;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
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
            font-size: 3rem;
            margin-bottom: 30px;
            margin-top: 30px;
            font-family: "Satisfy";
        }
        h3 {
            margin-top: 30px;
            text-align: center;
            color: #213555; 
            font-size: 1.5rem;
            font-family: "Satisfy";
        }
        p{
            text-align: center;
            font-size: 1.3rem;
            font-family:  serif;
            color: #333;
            margin-bottom: 10px;
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
       
       
        </style>
