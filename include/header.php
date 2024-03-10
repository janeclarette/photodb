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
            <a href="/photodb/customer/customerdashboard.php"><img src="../uploads/C.png" alt="Logo"></a>
        </div>

        <div class="profile">
            <!-- Profile (upper right corner) -->
            <div class="logout">
                <!-- Logout link -->
                <a href="/photodb/admin/logout.php"><i class="fas fa-sign-in-alt"></i></a>
            </div>
        </div>
    </header>

</body>
</html>


