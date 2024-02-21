<?php
session_start();
include("../include/config.php");

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
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

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
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
</head>

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
</body>
</html>
<?php
mysqli_close($conn);
?>