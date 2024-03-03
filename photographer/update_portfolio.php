<?php
session_start();
include("../include/config.php");
include("../photographer/header.php");

if (isset($_SESSION['PhotographerID'])) {
    $photographerID = $_SESSION['PhotographerID'];

    if (isset($_GET['workID'])) {
        $workID = $_GET['workID'];

        $sql = "SELECT * FROM works WHERE WorkID=$workID AND PhotographerID=$photographerID";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $albumData = $result->fetch_assoc();
        } else {
            echo "Album not found.";
            exit();
        }
    } else {
        echo "WorkID not provided.";
        exit();
    }
} else {
    header("Location: /photodb/admin/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        // Delete album from the database
        $sql = "DELETE FROM works WHERE WorkID=$workID AND PhotographerID=$photographerID";
        $result = $conn->query($sql);

        if ($result) {
            echo '<script>';
            echo 'alert("Album deleted successfully");';
            echo 'window.location.href = "work_create.php";';
            echo '</script>';
            exit();
        } else {
            echo "Error deleting album: " . $conn->error;
        }
    }

    // Update album information in the database
    $album = $conn->real_escape_string($_POST['album']);
    $description = $conn->real_escape_string($_POST['description']);
    $serviceType = $_POST['serviceType'];

    // Check if new images are uploaded
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

        // Update Photos column with new image paths
        $sql = "UPDATE works SET Album='$album', Description='$description', ServiceTypeID='$serviceType', Photos='$allImagePathsString' WHERE WorkID=$workID AND PhotographerID=$photographerID";
    } else {
        // No new images uploaded, update other fields only
        $sql = "UPDATE works SET Album='$album', Description='$description', ServiceTypeID='$serviceType' WHERE WorkID=$workID AND PhotographerID=$photographerID";
    }

    $result = $conn->query($sql);

    if ($result) {
        echo '<script>';
        echo 'alert("Album updated successfully");';
        echo 'window.location.href = "work_create.php";';
        echo '</script>';
    } else {
        echo "Error updating album: " . $conn->error;
    }
}
?>

<body>
    <h2>Update Album</h2>

    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="slideshow-container">
                <?php
                $imagePaths = explode(',', $albumData['Photos']);
                foreach ($imagePaths as $imagePath) {
                    echo "<img src='$imagePath' alt='Album Image' class='slideshow-image'>";
                }
                ?>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const slideshowImages = document.querySelectorAll('.slideshow-image');
                    let currentImageIndex = 0;

                    function showNextImage() {
                        slideshowImages[currentImageIndex].classList.remove('active');
                        currentImageIndex = (currentImageIndex + 1) % slideshowImages.length;
                        slideshowImages[currentImageIndex].classList.add('active');
                    }

                    if (slideshowImages.length > 0) {
                        slideshowImages[currentImageIndex].classList.add('active');
                        setInterval(showNextImage, 2000);
                    }
                });
            </script>

            <label for="images">Select Images (Multiple):</label>
            <input type="file" name="images[]" id="images" multiple accept="image/*">
            <br>
            <label for="album">Album:</label>
            <input type="text" name="album" id="album" value="<?= $albumData['Album']; ?>" required>
            <br>
            <label for="serviceType">Service Type:</label>
            <select name="serviceType" id="serviceType" required>
                <?php
                $sql = "SELECT * FROM ServiceTypes";
                $result = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    $selected = ($row['ServiceTypeID'] == $albumData['ServiceTypeID']) ? 'selected' : '';
                    echo "<option value='" . $row['ServiceTypeID'] . "' $selected>" . $row['TypeName'] . "</option>";
                }
                ?>
            </select>
            <br>
            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="4" required><?= $albumData['Description']; ?></textarea>
            <br>
            <input type="submit" value="Update">
            <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this album? This action cannot be undone.');">
        </form>
    </div>
</body>
</html>

<style>
.slideshow-container {
            max-width: 300px; /* Set the maximum width of the slideshow container */
            overflow: hidden; /* Hide any overflowing content */
            position: relative;
            margin: auto;
        }

        .slideshow-image {
            width: 100%; /* Make the images fill the width of the container */
            height: auto; /* Maintain the aspect ratio of the images */
            display: none; /* Hide all images by default */
        }

        .slideshow-image.active {
            display: block; /* Show the active image */
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
            max-width: 500px;
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
