<?php include("../include/config.php"); ?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>General Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="../include/style.css">
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
                        <a href="/photodb/photographer/phregister.php"> Sign In as Photographer</a>
                        <a href="/photodb/customer/customerregister.php"> Sign In as Customer</a>
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
            <li><a href="#">Home</a></li>
            <!-- Dropdown for Photographers -->
            <li><a href="#">Photographers</a></li>
            <!-- Dropdown for Services -->
            <li class="dropdown">
            <a href="#">Services</a>
            <div class="dropdown-content">
                <?php
                // Fetch service types from the database
                $serviceTypesSql = "SELECT * FROM servicetypes";
                $serviceTypesResult = $conn->query($serviceTypesSql);
                while($serviceTypeRow = $serviceTypesResult->fetch_assoc()) {
                    echo "<a>{$serviceTypeRow['TypeName']}</a>";
                }
                ?>
            </div>
            </li>
            <li><a href="#">Reviews</a></li>
            <li><a href="#">Photo Gallery</a></li>
            <li><a href="#">Pricing</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact Us</a></li>
        </ul>
    </nav>

</body>
</html>


