<?php include("../include/config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About Us</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body>
    <!-- Main header with navigation bar -->
    <section class="background">
    <header class="navbar">
    <div class="logo">
        <!-- Logo (upper left corner) -->
        <a href="/photodb/customer/customerdashboard.php"><img src="../uploads/C.png" alt="Logo"></a>
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
            <li><a href="/photodb/customer/phreviews.php">Reviews</a></li>
            <li><a href="/photodb/customer/gallery.php">Photo Gallery</a></li>
            <li><a href="/photodb/customer/price.php">Pricing</a></li>
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

        <section class="services">
    <div class="container">
        <div class="about-us-container">
            <h2>Privacy Policy</h2>
            
            <h2>Information We Collect</h2>
            <p>Your privacy is important to us. It is CheeseClick's policy to respect your privacy regarding any information we may collect from you across our website, https://www.cheeseclick.com, and other sites we own and operate.
             We collect several types of information for various purposes to provide and improve our service to you.</p>
            
            <h2>How We Use Your Information</h2>
            <p>We only ask for personal information when we truly need it to provide a service to you. We collect it by fair and lawful means, with your knowledge and consent. We also let you know why we’re collecting it and how it will be used.
            Personal data we collect includes but is not limited to: Name, Email address, Phone number, Address, Payment information.
            We use the information we collect in various ways, including to provide, operate, and maintain our website; improve, personalize, and expand our website; understand and analyze how you use our website; and develop new products, services, features, and functionality.</p>
            
            <h2>Retention of Information</h2>
            <p>We only retain collected information for as long as necessary to provide you with your requested service and protect it within commercially acceptable means to prevent loss, theft, unauthorized access, disclosure, copying, use, or modification. What data we store, we’ll protect within commercially acceptable means to prevent loss and theft, as well as unauthorized access, disclosure, copying, use, or modification.</p><h2>Third-Party Disclosure</h2><p>We don’t share any personally identifying information publicly or with third-parties, except when required to by law. Our website may link to external sites that are not operated by us. Please be aware that we have no control over the content and practices of these sites, and cannot accept responsibility or liability for their respective privacy policies.</p><h2>Refusal of Information</h2><p>You are free to refuse our request for your personal information, with the understanding that we may be unable to provide you with some of your desired services. Your continued use of our website will be regarded as acceptance of our practices around privacy and personal information. If you have any questions about how we handle user data and personal information, feel free to contact us. This policy is effective as of 1 January 2024.</p>
            
        </div>
    </div>
</section>



        <!-- Featured events section -->
       
        <?php include("../include/footer.php"); ?>
</body>
</html>




<style>

body {
   

        background-image: url('../uploads/cover.jpg');  /* Set the path to your cover image */
        background-size: cover;
        background-position: center bottom; /* Lower the background image */
    
}
    /* Resetting default margin and padding */
    body, h1, h2, h3, h4, h5, h6, p, ul, ol, li, figure, figcaption, blockquote, dl, dd, dt {
        margin: 0;
        padding: 0;
    }

    h2 {
        margin-top: 20px;
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


    

    /* Welcome section */
    .welcome {
        padding: 40px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .welcome h2 {
        text-align: center;
        font-size: 6rem;
        font-family: 'Satisfy';
        color: #333;
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
    .container {
    margin: 0 auto;
    padding: 20px;
}

.about-us-container {
    background-color: transparent; /* Sky blue color with transparency */
    border: 2px solid rgba(255,255,255, .5);
    backdrop-filter: blur(30px);
    padding: 30px 40px;
    color:#fff;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.15); /* Add shadow effect */
}

.about-us-container h2 {
    text-align: center;
    font-size: 3rem;
    color: #333;
    font-weight: bold;
    margin-bottom: 20px;
    text-transform: none;
    font-family: 'Satisfy';
    
}

.about-us-container p {
    font-size: 1.5rem;
    color: #fffff0;
    line-height: 1.6;
    font-family: 'Arial', sans-serif;}
</style>