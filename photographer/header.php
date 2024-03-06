<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photographer Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body, h1, h2, h3, h4, h5, h6, p, ul, ol, li, figure, figcaption, blockquote, dl, dd, dt {
            margin: 0;
            padding: 0;
        }

        body {
        
            overflow-x: hidden;
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            justify-content: space-between;
        }

        .profile {
            display: flex;
            align-items: center;
        }

        .sign-in,
        .logout,
        .message {
            margin-right: 20px;
        }

        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #4F709C;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: #fff;
            transition: 0.3s;
        }

        .sidebar a:hover {
            color: #E0F4FF;
        }

        .sidebar .close-btn {
            position: absolute;
            top: 0;
            right: 20px;
            font-size: 30px;
            margin-left: 50px;
        }
    </style>
</head>
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
                <a href="phprofile.php?photographerID=?"><i class="fa-regular fa-user"></i></a>
            </div>
            <div class="message">
                <a href="/photodb/photographer/message.php"><i class="fa-regular fa-message"></i></a>
            </div>
            <div class="logout">
                <a href="/photodb/general/view.php"><i class="fas fa-sign-in-alt"></i></a>
            </div>
        </div>
    </header>

    <div class="sidebar" id="mySidebar">
        <a href="phdashboard.php">Home</a>
        <a href="work_create.php">Portfolio</a>
        <a href="schedule.php">Schedule</a>
        <a href="gallery.php">Gallery</a>
        <a href="package.php">Package</a>
        <a href="place.php">Place</a>
        <a href="reviews.php">Reviews</a>
        <a href="phprofile.php?photographerID=?">Profile</a>
        <a href="/photodb/photographer/message.php">Messages</a>
        <a href="/photodb/general/view.php">Logout</a>
        <a href="javascript:void(0)" class="close-btn" onclick="toggleSidebar()">&times;</a>
    </div>

    <div class="container">
        <!-- Your existing content -->
    </div>

    <script>
        const sidebarTriggerWidth = 50;

        function toggleSidebar() {
            const sidebar = document.getElementById("mySidebar");
            const sidebarWidth = sidebar.offsetWidth;

            if (event.clientX <= sidebarTriggerWidth && sidebar.style.width !== "250px") {
                sidebar.style.width = "250px";
            } else if (event.clientX > sidebarWidth) {
                sidebar.style.width = "0";
            }
        }

        document.addEventListener("mousemove", toggleSidebar);
    </script>

</body>
</html>
