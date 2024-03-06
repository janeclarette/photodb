<?php
session_start();
include("../include/config.php");
include("../customer/header.php");

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
    // Redirect back to the gallery if no search query is provided
    header("Location: gallery.php");
    exit();
}

$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error executing query: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <!-- Add your CSS stylesheets here -->
    <style>
        /* Your CSS styles for the search results */
        /* Body and container styles */
        body {
            background-color: #E0F4FF;
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

        /* Image container styles */
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
    </style>
</head>
<body>

<div class="container">
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        $photoURLs = explode(',', $row['Photos']);
        foreach ($photoURLs as $photoURL) {
            $photographerName = isset($row['PhotographerName']) ? $row['PhotographerName'] : '';
            echo '<div class="image-container">';
            echo '<img src="' . trim($photoURL) . '" alt="Photograph" onclick="openModal(\'' . $photographerName . '\', \'' . $row['Album'] . '\', \'' . $row['Description'] . '\', \'' . $row['TypeName'] . '\', \'' . trim($photoURL) . '\')">';
            echo '</div>';
        }
    }
    ?>
</div>

<!-- Add your JavaScript code here if needed -->
<script>
    // JavaScript code if needed
</script>

</body>
</html>

<?php
mysqli_close($conn);
?>
