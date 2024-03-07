<?php
// Include necessary files and establish a database connection
session_start ();
include("../include/config.php"); 

?>


    <title>Customer Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <body>

    <section class="background">
    <header class="navbar">
    <div class="logo">
        <!-- Logo (upper left corner) -->
        <a href="#"><img src="../uploads/C.png" alt="Logo"></a>
    </div>

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
        <a href="/photodb/customer/message.php"><i class="fa-regular fa-message"></i></a>
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
            <li><a href="/photodb/customer/customerdashboard.php">Home</a></li>       
            <li><a href="/photodb/customer/photographer.php">Photographers</a></li>


            <li class="dropdown">
            <a href="services.php">Services</a>
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
            <li><a href="/photodb/customer/phreviews.php">Reviews</a></li>
            <li><a href="/photodb/customer/gallery.php">Photo Gallery</a></li>
            <li><a href="/photodb/customer/price.php">Packages</a></li>
            <li><a href="/photodb/customer/appointment.php">Appointment</a></li>
            <li><a href="/photodb/customer/aboutus.php">About Us</a></li>
        </ul>
    </nav>
    <!-- Main content of the page -->

</body>
</html>
    <!-- Main content of the page -->
    
        <!-- Welcome section -->
        <section class="welcome">
            <h2>Welcome to CheeseClick</h2>
        </section>
        </section>        

        <!-- Services section -->
        <section class="services">
            <h2>Our Services</h2>
            <p>Check out our range of services to meet your needs.</p>
            <div class="service-container">
        <div class="service">
            <img src="../uploads/wed.jpg" alt="Service 1">
            <h3>Wedding Photography</h3>
        </div>
        <div class="service">
            <img src="../uploads/spo.jpg" alt="Service 2">
            <h3>Sports Photography</h3>
        </div>
        <div class="service">
            <img src="../uploads/new.jpg" alt="Service 3">
            <h3> Newborn Photography</h3>
        </div>
        
        </section>

        <!-- Featured events section -->
        <section class="featured-events">
            <h2>Featured Events</h2>
            <p>Explore our featured events.</p>
           
        </section>
        <?php include("../include/footer.php"); ?>
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

        .sub-navbar ul {
        margin-top: 30px;
        list-style-type: none;
        display: flex;
        justify-content: center; /* Center the items horizontally */
        }

        .sub-navbar ul li a {
            color: #fff;
            text-decoration: none; /* Remove underline */
            font-size: 1.5rem;
            margin-right: 10px;
        }
        .sub-navbar ul li {
            margin: 0 5px; /* Add margin to create spacing between items */
            opacity: 0; /* Initially hide the items */
            transform: translateX(-50%); /* Start off-screen to the left */
            animation: revealFromCenter 2.5s ease forwards; /* Apply animation */
        }

        @keyframes revealFromCenter {
            from {
                opacity: 0;
                transform: translateX(-50%); /* Start off-screen to the left */
            }
            to {
                opacity: 1;
                transform: translateX(0); /* Move to original position */
            }
        }

          /* Dropdown menu */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #9BABB8;
            min-width: 300px;
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


        .background {
            background-image: url('../uploads/cover.jpg');  /* Set the path to your cover image */
            background-size: cover;
            background-position: center bottom; /* Lower the background image */
        }

        /* Welcome section */
        .welcome {
            padding: 40px;
            margin-bottom: 20px;
            text-align: center;
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

    