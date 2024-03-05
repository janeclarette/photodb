<?php
// Include necessary files and establish a database connection
include("../include/config.php"); 
include("../customer/header.php"); 
?>
    <title>Customer Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <body>

    <!-- Main content of the page -->
    
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

</body>
</html>



  <!-- Add your CSS stylesheets here -->
  <style>

    body {
        background-color: #E0F4FF;
    }
        /* Resetting default margin and padding */
   

        /* Welcome section */
        .welcome {
            padding: 40px;
            margin-bottom: 20px;
            text-align: center;
            background-image: url('../uploads/cover.jpg');  /* Set the path to your cover image */
            background-size: cover;
            background-position: center bottom; /* Lower the background image */
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