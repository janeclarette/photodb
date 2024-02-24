<?php
session_start();
include("../include/config.php");
include("../photographer/header.php");

if (!isset($_SESSION['PhotographerID'])) {
    header("Location: /photodb/photographer/login.php");
    exit();
}

$photographerID = mysqli_real_escape_string($conn, $_SESSION['PhotographerID']);

$query = "SELECT Photos FROM works WHERE PhotographerID = '$photographerID'";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error executing query: " . mysqli_error($conn);
    exit();
}
?>




<div class="container">
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        $photoURLs = explode(',', $row['Photos']);
        foreach ($photoURLs as $photoURL) {
    ?>
            <div class="image-container">
                <img src="<?php echo trim($photoURL); ?>" alt="Photograph">
            </div>
    <?php
        }
    }
    ?>
</div>

<?php
mysqli_close($conn);
?>

<style>
        body {
            background-color: #E0F4FF;
        }

        .container {
            max-width: 80%;
            margin-top:100px;
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
            flex: 0 0 calc(24.23% - 20px); 
            box-sizing: border-box; 
        }

        .image-container:hover {
            transform: scale(1.05); 
        }

        .image-container img {
            width: 100%; 
            height: auto;
            display: block;
        }
</style>