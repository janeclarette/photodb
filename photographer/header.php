<?php
// Include necessary files and establish a database connection
include("../include/config.php"); // Include your database connection
?>
    <title>Customer Page</title>
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
        <a href="/photodb/customer/customerdashboard.php"><img src="../uploads/C.png" alt="Logo"></a>
    </div>




</header>


    <div class="profile">
    <!-- Profile (upper right corner) -->
    <?php if (isset($_SESSION['CustomerID'])): ?>
        <?php
        $customerId = $_SESSION['CustomerID'];
        $sql = "SELECT * FROM customers WHERE CustomerID = '$customerId'";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $customerInfo = mysqli_fetch_assoc($result);
            ?>
            <div class="user-info">
                <?php if (!empty($customerInfo['img_customer'])): ?>
                    <!-- Link the image to the profile.php page -->
                    <a href="/photodb/customer/profile.php">
                        <img src="<?php echo $customerInfo['img_customer']; ?>" alt="Profile Image">
                    </a>
                <?php endif; ?>
                <span class="username">Welcome, <?php echo $customerInfo['Name']; ?></span>
            </div>
        <?php } ?>
    <?php endif; ?>



    <div class="message">
        <!-- Logout link -->
        <a href="/photodb/photographer/message.php"><i class="fa-regular fa-message"></i></a>
    </div>
    <div class="logout">
        <!-- Logout link -->
        <a href="/photodb/admin/logout.php"><i class="fas fa-sign-in-alt"></i></a>
    </div>
    <!-- Toggle button for sidebar -->

</div>

    
    </div>

    </header>
    <!-- Secondary navigation bar -->    
    <div class="left-side">
    <nav class="sub-navbar">
        <ul>
            <!-- Navigation links -->
            <li> <a href="phdashboard.php">Home</a></li>       
            <li> <a href="work_create.php">Portfolio</a></li>
            <li><a href="schedule.php">Schedule</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <!-- <li><a href="/photodb/customer/price.php">Packages</a></li> -->
            <li><a href="../photographer/package.php">Package</a></li>
            <li><a href="place.php">Place</a></li>
            <li><a href="reviews.php">Reviews</a></li>
            <li><a href="../photographer/aboutus.php">About Us</a></li>
        </ul>
    </nav>
    


    <!-- Main content of the page -->
</div>
</body>
</html>



  <!-- Add your CSS stylesheets here -->
  <style>

.left-side {
    position: fixed;
    top: 0;
    left: 0;
    width: 50px; /* Set the width of the left side area */
    height: 100%;/* Background color of the left side area */
    transition: width 0.3s; 
    z-index: 2;/* Add transition for smooth animation */
}

.left-side:hover .sub-navbar {
    left: 0; /* Show the sub-navbar when hovering over the left side */
}

.content {
    margin-left: 50px; /* Adjust content margin to make space for the left side navigation bar */
    transition: margin-left 0.3s; /* Add transition for smooth animation */
}

/* Adjust content margin when the left side navigation bar is expanded */
.left-side:hover + .content {
    margin-left: 200px; /* Adjust the width of the left side navigation bar */
}



    body {
        background-color: #E0F4FF;
        
    }
        /* Resetting default margin and padding */

        /* Add your custom styles for the header and navigation bars */
        .navbar {
            /* Styles for the main navigation bar */
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
            margin-right: 5px;
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
    left: -300; /* Initially hidden off-screen to the left */
    height: 100vh;
    width: 200px;
    overflow-x: hidden;
    transition: left 0.3s;
    text-align: center;
    z-index: 1;
}

/* Show the sub-navbar when hovering over the left side of the content */

.sub-navbar ul {
        list-style-type: none;
        padding: 0;
    }




    .sub-navbar ul li {
        margin: 25px 0;
        margin-bottom: 30px;
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

        .message{
            margin-right: 10px; /* Adjust the margin between the items */
        }

        /* Welcome section */
        .welcome {
            padding: 40px;
            margin-bottom: 20px;
            text-align: center;
/* Lower the background image */
            height: 500px; /* Adjust the height as needed */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome h2 {
            text-align: center;
            font-size: 6rem;
            font-family: 'Satisfy';
            color: #fff;
        }

        /* Services section */
        .services {
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
            font-family:  serif;
            color: #333;
            margin: 20px;
        }
        .services p {
            text-align: center;
            font-size: 1.5rem;
            font-family:  serif;
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
 
    
