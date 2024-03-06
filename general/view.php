<?php include("../include/config.php"); ?><!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>General Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body>
    <!-- Main header with navigation bar -->
    <header class="navbar">
        <div class="logo">
            <!-- Logo (upper left corner) -->
            <a href="#"><img src="../uploads/C.png" alt="Logo"></a>
        </div>
        <div class="search">
            <!-- Search (center) -->
            <input type="text" placeholder="Search">
            <button type="submit">Search</button>
        </div>
        <div class="profile">
            <!-- Profile (upper right corner) -->
            <div class="sign-in">
                <!-- Dropdown for Sign In -->
                <div class="dropdown">
                    <button class="dropbtn"><i class="fa-regular fa-user"></i></button>
                    <div class="dropdown-content">
                        <a href="/photodb/admin/login.php"> Login </a>
                        <a href="/photodb/photographer/phregister.php"> Register as Photographer</a>
                        <a href="/photodb/customer/customerregister.php"> Register as Customer</a>
                    </div>
                </div>
            </div>
            <div class="logout">
                <!-- Logout link -->
                <a href="/photodb/admin/logout.php"><i class="fas fa-sign-in-alt"></i></a>
            </div>
        </div>
    </header>
    <!-- Secondary navigation bar -->
    <nav class="sub-navbar">
        <ul>
            <!-- Navigation links -->
            <li><a href="/photodb/customer/customerdashboard.php">Home</a></li>       
            <li><a href="/photodb/customer/photographer.php">Photographers</a></li>


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
            <li><a href="/photodb/customer/review.php">Reviews</a></li>
            <li><a href="/photodb/customer/gallery.php">Photo Gallery</a></li>
            <li><a href="/photodb/customer/price.php">Pricing</a></li>
            <li><a href="/photodb/admin/aboutus.php">About Us</a></li>
            <li><a href="/photodb/admin/contactus.php">Contact Us</a></li>
        </ul>
    </nav>
    <!-- Main content of the page -->
    <div class="container">
        <!-- Welcome section -->
        <section class="welcome">
            <h2>Welcome to CheeseClick</h2>
        </section>
        <!-- Services section -->
        <section class="services">
            <h2>Our Services</h2>
            <p>Check out our range of services to meet your needs.</p>
            <div class="service-container">
                <div class="service">
                    <img src="../uploads/wed.jpg" alt="Service 1">
                    <h3>Wedding Photography</h3>
                    <h6>Description of Service 1</h6>
                </div>
                <div class="service">
                    <img src="service_icons/service2.png" alt="Service 2">
                    <h3>Service 2</h3>
                    <p>Description of Service 2</p>
                </div>
                <div class="service">
                    <img src="service_icons/service3.png" alt="Service 3">
                    <h3>Service 3</h3>
                    <p>Description of Service 3</p>
                </div>
            </div>
        </section>
        <!-- Featured events section -->
        <section class="featured-events">
            <h2>Featured Events</h2>
            <p>Explore our featured events.</p>
        </section>
    </div>
</body>
</html>


<style>
    /* Resetting default margin and padding */
    body, h1, h2, h3, h4, h5, h6, p, ul, ol, li, figure, figcaption, blockquote, dl, dd, dt {
        margin: 0;
        padding: 0;
    }

    /* Add your custom styles for the header and navigation bars */
    .navbar {
        /* Styles for the main navigation bar */
        background-color: #213555;
        color: #fff;
        padding: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .navbar .logo img {
        margin-left: 40px;
        height: 80px; /* Adjust as needed */
        width: auto; /* Ensures the image scales with height */
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
        /* Styles for the secondary navigation bar */
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
    
    /* Dropdown menu */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #9BABB8;
        min-width: 160px;
        z-index: 1;
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

    /* Container for sections */
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
        margin-right: 40px; /* Adjust the margin between the items */
    }

    .sign-in .dropdown,
    .logout a {
        padding: 25px; /* Adjust the padding for better spacing */
    }


    /* Welcome section */
    .welcome {
        background-color: #f0f0f0;
        padding: 40px;
        margin-bottom: 20px;
        text-align: center;
        background-image: url('../uploads/cover.jpg');  /* Set the path to your cover image */
        background-size: cover;
        background-position: center bottom; /* Lower the background image */
        height: 400px; /* Adjust the height as needed */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .welcome h2 {
        text-align: center;
        font-size: 6rem;
        font-family: 'Satisfy';
        color: #FEFBF6;
    }

    /* Services section */
    .services {
        background-color: #F5EFE7;
        padding: 50px;
        margin-bottom: 20px;
        text-align: center;
    }

    .services h2 {
        text-align: center;
        font-size: 3rem;
        font-family: 'Satisfy';
        color: #333;
    }
    
    .services h3 {
        text-align: center;
        font-size: 2rem;
        font-family: 'Satisfy';
        color: #333;
    }

    .services h6 {
        text-align: center;
        font-size: 1.5rem;
        font-family: 'Cinzel', serif;
        color: #333;
        margin: 20px;
    }

    .services p {
        text-align: center;
        font-size: 1.5rem;
        font-family: 'Cinzel', serif;
        color: #333;
    }

    .service-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center; /* Centers the items horizontally */
    }

    .service {
        width: 200px; /* Adjust the width of each service */
        margin: 40px; /* Adjust the spacing between services */
        text-align: center;
    }

    .service img {
        width: 150px; /* Adjust the width of the service icons */
        height: auto;
        margin: 20px;
    }

    /* Featured events section */
    .featured-events {
        background-color: #F5EFE7;
        padding: 50px;
        margin-bottom: 20px;
        text-align: center;
    }

    .featured-events h2 {
        text-align: center;
        font-size: 3rem;
        font-family: 'Satisfy';
        color: #333;
    }

    .featured-events p {
        text-align: center;
        font-size: 1.5rem;
        font-family: 'Cinzel', serif;
        color: #333;
    }
</style>
