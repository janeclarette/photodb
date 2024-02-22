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
                <a href="/photodb/customer/profile.php"> <i class="fa-regular fa-user"></i></a>
    </div>
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
            <li><a href="/photodb/customer/review.php">Reviews</a></li>
            <li><a href="/photodb/customer/gallery.php">Photo Gallery</a></li>
            <li><a href="/photodb/customer/price.php">Pricing</a></li>
            <li><a href="/photodb/admin/aboutus.php">About Us</a></li>
            <li><a href="/photodb/admin/contactus.php">Contact Us</a></li>
        </ul>
    </nav>
    <!-- Main content of the page -->
    

    
    <section class="services">
        <h2>Our Photographers</h2>
        <p>Meet our talented photographers.</p>
        <div class="photographer-container">
            <?php
            // Fetch photographers from the database
            $photographersSql = "SELECT * FROM Photographers";
            $photographersResult = $conn->query($photographersSql);

            // Check for SQL query execution error
            if (!$photographersResult) {
                die("Error in SQL query: " . $conn->error);
            }

            while ($photographer = $photographersResult->fetch_assoc()) {
                echo '<div class="photographer">';
                echo '<img src="../uploads/' . $photographer['img_photographer'] . '" alt="' . $photographer['Name'] . '">';
                echo '<h3>Name: ' . $photographer['Name'] . '</h3>';
                echo '<p>Phone: ' . $photographer['Phone_Number'] . '</p>';
                echo '<p>Email: ' . $photographer['Email'] . '</p>';
                echo '<form action="view_album.php" method="GET" style="display: inline;">';
                echo '<input type="hidden" name="photographer_id" value="' . $photographer['PhotographerID'] . '">';
                echo '<button type="submit" style="background-color: #4F709C; color: #fff; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">View Albums</button>';
                echo '</form>';

                echo '</div>';
            }
            ?>
        </div>
    </section>

<!-- JavaScript code -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var viewButtons = document.querySelectorAll('.view-profile');

        viewButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var photographerID = button.getAttribute('data-photographer-id');

                // Display a basic alert for demonstration purposes
                alert('Photographer ID: ' + photographerID);

                // You can replace the alert with an AJAX request to fetch more details
                // For simplicity, I'm demonstrating only the ID here
            });
        });
    });
</script>

    <!-- Other HTML code ... -->
</body>
</html>



  <!-- Add your CSS stylesheets here -->
  <style>
    body {
        background-color: #607EAA;
        
    }
        /* Resetting default margin and padding */
        body, h1, h3, h4, h5, h6, p, ul, ol, li, figure, figcaption, blockquote, dl, dd, dt {
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


        .photographer-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        animation: fadeInUp 1s ease-out; /* Add fade-in animation */
    }

    .photographer {
    border-radius: 10px;
    padding: 20px;
    margin: 20px;
    text-align: center;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.5s ease-in-out, background-color 0.5s ease-in-out; /* Include transition for background-color */
    background-color: #FEFBF6;
}

.photographer:hover {
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    background-color: #DCF2F1; /* Adjust background color on hover */
}

    .photographer img {
        width: 150px;   
        height: auto;
        margin: 10px;
        border-radius: 100px; /* Make the image circular */
    }

    .photographer h3 {
        font-size: 1.5rem;
        font-family: 'Satisfy';
        color: #333;
    }

    .photographer p {
        font-size: 1rem;
        color: #333;
    }

    .view-profile {
        padding: 10px;
        background-color: #FEFBF6;
        color: #FEFBF6;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    /* Animation keyframes */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .albums-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .album-card {
        width: 200px; /* Adjust the width of each album */
        margin: 20px; /* Adjust the spacing between albums */
        text-align: center;
        background-color: #FEFBF6; /* Adjust background color as needed */
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease-in-out;
    }

    .album-card:hover {
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    }
    h2 {
            text-align: center;
            font-size: 4rem;
            font-family: 'Satisfy';
            color: #FEFBF6;
        }
         p {
            text-align: center;
            font-size: 1.5rem;
            font-family: 'Cinzel', serif;
            color: #FEFBF6;
            margin-bottom: 10px;
        }
    </style>

