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

 <!-- Main header with navigation bar -->
<header class="navbar">
    <div class="logo">
        <!-- Logo (upper left corner) -->
        <a href="/photodb/admin/admindashboard.php"><img src="../uploads/C.png" alt="Logo"></a>
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
                <!-- Dropdown for Sign In -->
                <div class="dropdown">
                    <button class="dropbtn"><i class="fas fa-envelope"></i></button>
                    <div class="dropdown-content">
                        <a href="/photodb/admin/message.php"> Photographers</a>
                        <a href="/photodb/admin/amessage.php">  Customers</a>

                    </div>
                </div>
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
    <div class="left-side">
    <nav class="sub-navbar">
        <ul>
            <!-- Navigation links -->
            <li><a href="/photodb/admin/admindashboard.php">Home</a></li>       
            <li><a href="/photodb/admin/sales.php">Statistics</a></li>
            <li><a href="/photodb/admin/transaction.php">Transactions</a></li>
            <li><a href="/photodb/admin/reviews.php">Reviews</a></li>
            <li><a href="/photodb/admin/photographers.php">Photographers</a></li>
            <li><a href="/photodb/admin/customers.php">Customers</a></li> 
        </ul>
    </nav>
</div>
    <!-- Main content of the page -->

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
    transition: width 0.3s; /* Add transition for smooth animation */
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

    .message{
            margin-right: 10px; /* Adjust the margin between the items */
        }
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
            text-align: center;
        background-color: rgba(75, 192, 192, 20);
        color: #fff;
        padding: 10px;
        position: fixed;
        top: 0;
        left: -300px; /* Initially hidden off-screen to the left */
        height: 100vh;
        width: 150px;
        overflow-x: hidden;
        transition: left 0.3s;
    }

    /* Show the sub-navbar when hovering over the left side of the screen */


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

.message{
            margin-right: 10px; /* Adjust the margin between the items */
        }
  
       
</style>
    