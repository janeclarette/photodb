<?php
// Include necessary files and establish a database connection
include("../include/config.php"); // Include your database connection


?>
    <title>Photographer Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
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
    <a href="phprofile.php?photographerID=?"><i class="fa-regular fa-user"></i></a>
    </div>
    <div class="message">
        <!-- Logout link -->
        <a href="/photodb/photographer/message.php"><i class="fa-regular fa-message"></i></a>
    </div>
    <div class="logout">
        <!-- Logout link -->
        <a href="/photodb/admin/logout.php"><i class="fas fa-sign-in-alt"></i></a>
    </div>

</div>

    
    </div>

    </header>
    <!-- Secondary navigation bar -->
    <nav class="sub-navbar">
        <ul>
            <!-- Navigation links -->
            <li><a href="#">Home</a></li>
            <li><a href="work_create.php">Portfolio</a></li>
            <li><a href="schedule.php">Schedule</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="package.php">Package</a></li>
            <li><a href="place.php">Place</a></li>
            <li><a href="#">Reviews</a></li>
        </ul>
    </nav>
    
  <!-- Add your CSS stylesheets here -->
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
        .logout{
            margin-right: 40px; /* Adjust the margin between the items */
        }

        .sign-in .dropdown,
        .logout a {
            padding: 25px; /* Adjust the padding for better spacing */
        }

        .message{
            margin-right: 10px; /* Adjust the margin between the items */
        }
        </style>