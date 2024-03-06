<?php
session_start();
include("../include/config.php");
include("../customer/header.php");


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
        <p id="modalPhotographerName" style="margin-bottom: 10px;"></p>
        <p id="modalAlbumTitle"></p>
        <p id="modalDescription"></p>
        <p id="modalTypeName"></p>
        <div class="download-container">
            <a id="downloadBtn" href="#" download>Download</a>
        </div>
        <img id="modalImage" src="" alt="Photograph">
    </div>
</div>

<?php
mysqli_close($conn);
?>

<style>
    body {
        background-color: #E0F4FF;
    }

    .search-container {
    text-align: center;
    display: flex 1;
    
}

.search-container form {
    display: inline-block;
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.search-container input[type="text"] {
    padding: 5px;
    margin-right: 5px;
    border: 1px solid #ccc;
    border-radius: 3px;
}

.search-container button {
    padding: 5px 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    margin-left: 5px;
}

.search-container button:hover {
    background-color: #0056b3;
}

    .container {
        max-width: 80%;
        margin-top: 100px;
        padding: 40px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        border-radius: 20px;
    }

    .image-container {
        margin-bottom: 20px;
        border: 2px solid #ddd;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
        width: 300px;
        height: 200px; /* Set a fixed height for all images */
        flex: 0 0 calc(24.23% - 20px);
        box-sizing: border-box;
    }

    .image-container:hover {
        transform: scale(1.05);
    }

    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Maintain aspect ratio and cover the entire container */
        display: block;
    }

    .image-container p {
        margin: 0;
        padding: 10px;
        background-color: #f8f8f8;
        text-align: center;
    }

    h2 {
            text-align: center;
            font-size: 4rem;
            font-family: 'Satisfy';
            color: #333;
        }
        p {
            text-align: center;
            font-size: 1.5rem;
            font-family: 'Cinzel', serif;
            color: #333;
            margin-bottom: 10px;
            color: white;
        }   

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.9);
    }

    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        max-height: 80%;
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

    .modal-content img {
        width: 100%;
        height: auto;
    }

    .download-container {
        text-align: center;
        margin-top: 10px;
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
        modalTypeName.innerHTML = "Service Type Name: " + TypeName;
        downloadBtn.href = photoURL;
    }

    var closeBtn = document.getElementsByClassName("close")[0];
    closeBtn.onclick = function() {
        var modal = document.getElementById("myModal");
        modal.style.display = "none";
    }
</script>
