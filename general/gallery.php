<?php
session_start();
include("../include/config.php");
if (!isset($_SESSION['CustomerID'])) {
    // Display JavaScript alert before redirection
    echo '<script>alert("Please login first!");</script>'; 
    // Redirect to login page or handle the case when the customer is not logged in
    echo '<script>window.location.href = "../admin/login.php";</script>';
    exit();
}



$query = "SELECT Photos, PhotographerID FROM works";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error executing query: " . mysqli_error($conn);
    exit();
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

if (!empty($search)) {
    $query = "SELECT w.Photos, p.Name AS PhotographerName, w.Album, w.Description, s.TypeName, w.ServiceTypeID
    FROM works w
    JOIN photographers p ON w.PhotographerID = p.PhotographerID
    JOIN servicetypes s ON w.ServiceTypeID = s.ServiceTypeID
    WHERE p.Name LIKE '%$search%'
    OR w.Album LIKE '%$search%'
    OR w.Description LIKE '%$search%'
    OR s.TypeName LIKE '%$search%'";
} else {
    $query = "SELECT w.Photos, p.Name AS PhotographerName, w.Album, w.Description, s.TypeName, w.ServiceTypeID
    FROM works w
    JOIN photographers p ON w.PhotographerID = p.PhotographerID
    JOIN servicetypes s ON w.ServiceTypeID = s.ServiceTypeID";
}

$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error executing query: " . mysqli_error($conn);
    exit();
}
?>
<section class="background">
<div class="gallery-title">
    <h2>Photo Gallery</h2>
</div>
<div class="search-container">
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search...">
        <button type="submit">Search</button>
    </form>
</div>

<div class="container">
    <?php
    $photographerName = '';
    while ($row = mysqli_fetch_assoc($result)) {
        $photoURLs = explode(',', $row['Photos']);
        foreach ($photoURLs as $photoURL) {
            $photoPhotographerID = isset($row['PhotographerID']) ? $row['PhotographerID'] : '';
            if (!empty($photoPhotographerID)) {
                $photographerQuery = "SELECT * FROM photographers WHERE PhotographerID = '$photoPhotographerID'";
                $photographerResult = mysqli_query($conn, $photographerQuery);
                $photographerInfo = mysqli_fetch_assoc($photographerResult);
                $photographerName = isset($photographerInfo['Name']) ? $photographerInfo['Name'] : '';
            }
            echo '<div class="image-container">';
            echo '<img src="' . trim($photoURL) . '" alt="Photograph" onclick="openModal(\'' . $photographerName . '\', \'' . $row['Album'] . '\', \'' . $row['Description'] . '\', \'' . $row['TypeName'] . '\', \'' . trim($photoURL) . '\')">';
            echo '</div>';
        }
    }
    ?>
</div>

<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="modal-text">
            <p id="modalPhotographerName"></p>
            <p id="modalAlbumTitle"></p>
            <p id="modalDescription"></p>
            <p id="modalTypeName"></p>
            <div class="download-container">
                <a id="downloadBtn" href="#" download>Download</a>
            </div>
        </div>
        <div class="modal-image">
            <img id="modalImage" src="" alt="Photograph">
        </div>
    </div>
</div>


<?php
mysqli_close($conn);
?>
<style>
    body {
        background-color: #fff;
        font-family: 'serif';
        color: #fff;
    }


    .background {
            background-image: url('../uploads/cover.jpg');  /* Set the path to your cover image */
            background-size: cover;
            background-position: center top; /* Lower the background image */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 50%;
            z-index: -1; /* Push the background behind other content */
        }

    .gallery-title {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 40px;
    height: 250px;
}

.gallery-title > h2 {
    margin-top: 70px;
    font-size: 7rem;
    color: #333;
    font-family: 'Satisfy';


}


    .search-container {
    position: absolute;
    top: 0;
    right: 0;
    margin: 20px; /* Adjust as needed */
    z-index: 999; /* Ensure it overlays other elements */
    margin-right: 570px; 
}


    .search-container form {
        display: inline-block;
        padding: 10px;
        border-radius: 5px;

    }

    .search-container input[type="text"] {
        padding: 10px;
            border: none;
            border-radius: 10px;
            margin-right: 10px;
            width: 300px;
    }

    .search-container button {
        padding: 5px 10px;
            background-color: #4F709C;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
    }

    .search-container button:hover {
        background-color: #0056b3;
    }

    .container {
    margin-top: 250px; 
    max-width: 80%;
    padding: 40px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    border-radius: 20px;
}

.image-container {
    margin-bottom: 100px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
    width: 400px; /* Change this to adjust the width */
    height: 550px; /* Change this to adjust the height */
    flex: 0 0 calc(49.23% - 20px); /* Adjust the width of each container */
    box-sizing: border-box;
}

.image-container:hover {
    transform: scale(1.05);
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}


    .modal {
        display: none;
        text-align: center;
        position: fixed;
        z-index: 1;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.9);
        margin-bottom: 30px;
    }

    .modal-content {
    margin: auto;
    display: flex;
    width: 80%;
    max-width: 1000px;
    max-height: 80%;
}

.modal-text {
    flex: 1;
    padding: 20px;
    margin-top: 200px;
}

.modal-image {
    flex: 1;
    padding: 50px;
}

.modal-content img {
    max-width: 500px;
    max-height: 600px;
    object-fit: contain;
}


    .modal p {
        margin-bottom: 20px;
    }

    .close {
        color: white;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #ccc;
        text-decoration: none;
        cursor: pointer;
    }



    .download-container {
        text-align: center;
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .download-container a {
        color: white;
        text-decoration: none;
        background-color: #007bff;
        padding: 8px 16px;
        border-radius: 5px;
    }

    .download-container a:hover {
        background-color: #0056b3;
    }
    
</style>


<script>
    function openModal(photographerName, albumTitle, description, TypeName, photoURL) {
        var modal = document.getElementById("myModal");
        var modalImg = document.getElementById("modalImage");
        var modalPhotographerName = document.getElementById("modalPhotographerName");
        var modalAlbumTitle = document.getElementById("modalAlbumTitle");
        var modalDescription = document.getElementById("modalDescription");
        var modalTypeName = document.getElementById("modalTypeName");
        var downloadBtn = document.getElementById("downloadBtn");

        modal.style.display = "block";
        modalImg.src = photoURL;
        modalPhotographerName.innerHTML = "Photographer: " + photographerName;
        modalAlbumTitle.innerHTML = "Album: " + albumTitle;
        modalDescription.innerHTML = "Description: " + description;
        modalTypeName.innerHTML = "Service Type: " + TypeName;
        downloadBtn.href = photoURL;
    }

    var closeBtn = document.getElementsByClassName("close")[0];
    closeBtn.onclick = function () {
        var modal = document.getElementById("myModal");
        modal.style.display = "none";
    }
</script>

<?php include("../include/footer.php"); ?>

</body>