<?php
// Include necessary files and establish a database connection
include("../include/config.php"); // Include your database connection
?>
    <title>Admin Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <body>
    <!-- Main header with navigation bar -->
    <header class="navbar">
 <!-- Main header with navigation bar -->
<header class="navbar">
    <div class="logo">
        <!-- Logo (upper left corner) -->
        <a href="#"><img src="../uploads/C.png" alt="Logo"></a>
    </div>

    <div class="profile">
        <div class="sign-in">
        <!-- Dropdown for Sign In -->
        <div class="dropdown">
            <button class="dropbtn"><i class="fa-regular fa-user"></i></button>
            <div class="dropdown-content">
                <a href="/photodb/admin/adminregister.php"> Register</a>
            </div>
        </div>
    </div>
    
    <div class="message">
        <!-- Logout link -->
        <a href="/photodb/admin/message.php"><i class="fa-regular fa-message"></i></a>
    </div>
    <div class="logout">
        <!-- Logout link -->
        <a href="/photodb/general/view.php"><i class="fas fa-sign-in-alt"></i></a>
    </div>

    </div> 
    </div>

    </header>

    </header>
    <!-- Secondary navigation bar -->
    <nav class="sub-navbar">
        <ul>
            <!-- Navigation links -->
            <li><a href="/photodb/admin/admindashboard.php">Home</a></li>       
            <li><a href="/photodb/admin/sales.php">Statistic</a></li>
            <li><a href="/photodb/admin/reviews.php">Reviews</a></li>
            <li><a href="/photodb/admin/photographers.php">Photographers</a></li>
            <li><a href="/photodb/admin/customers.php">Customers</a></li>
        </ul>
    </nav>
    <!-- Main content of the page -->
<div class="overlay"></div>
</body>
</html>



  <!-- Add your CSS stylesheets here -->
  <style>

body {
        background-color: #E0F4FF;
    }
        /* Resetting default margin and padding */
        body, h1, h2, h3, h4, h5, h6, p, ul, ol, li, figure, figcaption, blockquote, dl, dd, dt {
            margin: 0;
            padding: 0;
        }

        /* Add your custom styles for the header and navigation bars */
       

        .navbar .logo img {
            margin-left: 40px;
            margin-top: 20px;
            height: 80px; /* Adjust as needed */
            width: auto; /* Ensures the image scales with height */
        }


        .navbar .profile a {
            color: #fff;
            text-decoration: none;
            
        }
        .navbar .profile .user-info {
            display: flex;
            align-items: center;
            margin-right: 15px;
        }

        .navbar .profile .user-info img {
            width: 30px; /* Adjust image size as needed */
            height: 30px; /* Adjust image size as needed */
            border-radius: 50%;
            margin-right: 5px;
        }

        .navbar .profile .user-info .username {
            color: #fff;
            font-size: 14px;
        }

        .sub-navbar {
        background-color: rgba(75, 192, 192, 20);
        color: #fff;
        padding: 10px;
        position: fixed;
        top: 0;
        left: -200px; /* Initially hidden off-screen to the left */
        height: 100vh;
        width: 150px;
        overflow-x: hidden;
        transition: left 0.3s;
    }

    /* Show the sub-navbar when hovering over the left side of the screen */
    body:hover .sub-navbar,
body:hover .content {
    left: 0;
}

    .sub-navbar ul {
        list-style-type: none;
        padding: 0;
    }

    .sub-navbar ul li {
        margin: 5px 0;
    }

    .sub-navbar ul li a {
        color: #fff;
        text-decoration: none;
        display: block;
        padding: 10px;
        transition: background-color 0.3s;
    }

    .sub-navbar ul li a:hover {
        background-color: #32475C;
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
    justify-content: flex-end; /* Align items to the right */
    margin-right: 40px; /* Add margin for spacing */
}

.sign-in,
.message,
.logout {
   
    margin-left: 10px; /* Adjust spacing between items */
}

.sign-in .dropdown,
.logout a {
    padding: 15px; /* Adjust padding for better spacing */
}


  
       
</style>
    