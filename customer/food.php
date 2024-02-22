<?php
include("../include/config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
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
                <a href="/photodb/customer/profile.php"> <i class="fa-regular fa-user"></i></a>
    </div>
    <div class="message">
        <a href="/photodb/customer/message.php"><i class="fa-regular fa-message"></i></a>
    </div>
    <div class="logout">
        <a href="/photodb/admin/logout.php"><i class="fas fa-sign-in-alt"></i></a>
    </div>

</div>

    </header>


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

    <div class="container">
        <section class="welcome">
            <h2>Welcome to CheeseClick</h2>
        </section>
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

        <section class="featured-events">
            <h2>Featured Events</h2>
            <p>Explore our featured events.</p>
        </section>
    </div>

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
            margin-right: 40px; 
        }

        .sign-in .dropdown,
        .logout a {
            padding: 25px; 
        }

        .message{
            margin-right: 10px;
        }
        .welcome {
            background-color: #f0f0f0;
            padding: 40px;
            margin-bottom: 20px;
            text-align: center;
            background-image: url('../uploads/cover.jpg'); 
            background-size: cover;
            background-position: center bottom; 
            height: 400px;
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
            justify-content: center; 
        }

        .service {
            width: 200px; 
            margin: 40px; 
            text-align: center;
        }

        .service img {
            width: 150px; 
            height: auto;
            margin: 20px;
        }

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

        .works-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around; 
            margin: 0 -10px; 
            
        }

        .work-container {
            width: calc(33.33% - 20px);
            margin: 10px;
            max-width: 350px;
            box-sizing: border-box;
        }

        @media (max-width: 767px) {
            .work-container {
                width: calc(50% - 20px);
                max-width: 350px;
            }
        }

        @media (min-width: 768px) and (max-width: 1023px) {
            .work-container {
                width: calc(33.33% - 20px);
                max-width: 350px;
            }
        }

        @media (min-width: 1024px) {
            .work-container {
                width: calc(33.33% - 20px);
                max-width: 450px;
            }
        }

        .work {
            width: 100%;
            text-align: center;
            padding: 20px;
            background-color: #E9E4D4;
            height: 400px;
        }

        .work img {
    width: 100%;
    height: 350px; 
    object-fit: cover; 
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    max-width: 90%; 
    margin: 0 auto; 
    display: block;
}
        .work h3 {
            font-size: 1.5rem;
            margin-top: 10px;
        }

        .work p {
            font-size: 1rem;
            color: #777;
        }

        .work-id-container {
            background-color: #4F709C;
            color: #fff;
            padding: 10px;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
            width: 470px;
            text-align: center;
            
        }

        .work-container:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
        .work img:not(:first-child) {
  display: none;
        }
   
    </style>

    <?php
    $selectedServiceTypeID = '9';
    $selectedTypeName = 'Food Photography';

    $worksSql = "SELECT w.Photos, w.Album, p.Name, w.WorkID 
                 FROM works w
                 JOIN photographers p ON w.PhotographerID = p.PhotographerID
                 JOIN servicetypes st ON w.ServiceTypeID = st.ServiceTypeID
                 WHERE st.ServiceTypeID = $selectedServiceTypeID
                 AND st.TypeName = '$selectedTypeName'";
    $worksResult = $conn->query($worksSql);

    if ($worksResult->num_rows > 0) {
        echo '<div class="works-container">';
        while ($workRow = $worksResult->fetch_assoc()) {
            echo '<div class="work-container">'; 

            echo '<div class="work">';

            $photosArray = explode(',', $workRow['Photos']);

            foreach ($photosArray as $photo) {
                echo '<img src="' . $photo . '" alt="Work Image">';
            }

            echo '<h3>Album Title: ' . $workRow['Album'] . '</h3>';
            echo '</div>'; 
            echo '<div class="work-id-container">';
            echo '<p>Photographer Name: ' . $workRow['Name'] . '</p>';
            echo '</div>';

            echo '</div>';
        }

        echo '</div>';
    } else {
        echo '<p>No works found for the specified service type and typename.</p>';
    }
    ?>
</body>
</html>
<script>
  function changeSlide(containerIndex) {
    let currentSlide = 0; 
    const workContainers = document.querySelectorAll('.work-container'); 

    function showNextSlide() {
      const slides = workContainers[containerIndex].querySelectorAll('.work img'); 
      slides[currentSlide].style.display = 'none'; 
      currentSlide = (currentSlide + 1) % slides.length; 
      slides[currentSlide].style.display = 'block'; 
    }

    showNextSlide();
    setInterval(showNextSlide, 5000);
  }

  document.addEventListener('DOMContentLoaded', function() {
    const workContainers = document.querySelectorAll('.work-container');

    workContainers.forEach((container, index) => {
      changeSlide(index);
    });
  });
</script>