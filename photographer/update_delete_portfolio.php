<?php
session_start();
include("../include/config.php");
include("../photographer/header.php");

if (!isset($_SESSION['PhotographerID'])) {
    header("Location: /photodb/admin/login.php");
    exit();
}

if (!isset($_GET['album'])) {
    header("Location: /photodb/photographer/home.php");
    exit();
}

$albumID = $_GET['album'];
$photographerID = $_SESSION['PhotographerID'];

$sqlImages = "SELECT * FROM works WHERE PhotographerID = $photographerID AND Album = '$albumID'";
$resultImages = $conn->query($sqlImages);

if (!$resultImages || $resultImages->num_rows === 0) {
    echo "No images found for this album.";
    exit();
}
?>

<body>
<h2>Update/Delete Images</h2>

<div class="container">
    <div class="albums-container">
        <?php while ($image = $resultImages->fetch_assoc()) : ?>
            <div class="album-card">
                <img src="<?= $image['Photos']; ?>" alt="Album Image" class="slideshow-image">
                <h3>Image Description: <?= $image['Description']; ?></h3>
                <p>Service Type: <?= $image['ServiceTypeID']; ?></p>
                <form action="" method="post">
                    <input type="hidden" name="imageID" value="<?= $image['ImageID']; ?>">
                    <input type="submit" name="update" value="Update">
                    <input type="submit" name="delete" value="Delete">
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
