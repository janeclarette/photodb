<?php include("../include/config.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
    <link rel="stylesheet" href="style.css"> <!-- Include your CSS file -->
</head>
<body>
    <h2>Customer Registration</h2>
    <form action="customerstore.php" method="post" class="form-outline" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" class="form-control" required><br>
            </div><br>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label><br>
                <input type="text" id="phone_number" name="phone_number" class="form-control" required><br>
            </div>
        </div><br>
        <div class="form-row">
            <div class="form-group">
                <label for="address">Address:</label><br>
                <textarea id="address" name="address" class="form-control" required></textarea><br>
            </div>
            <div class="form-group">
                <label for="city_id">City:</label><br>
                <select name="city_id" id="city_id" class="form-control" required>
                    <?php
                    $sql = "SELECT * FROM cities";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['CityID']}'>{$row['CityName']}</option>";
                        }
                    }
                    ?>
                </select><br>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" class="form-control" required><br>
            </div>
            <div class="form-group">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" class="form-control" required><br>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" class="form-control" required><br>
            </div>
            <div class="form-group">
                <label for="img_customer">Profile Image:</label><br>
                <input type="file" id="img_customer" name="img_customer" class="form-control"><br>
            </div>
        </div><br>
        <div class="form-group">
                <label for="gcash_number">Gcash Number:</label><br>
                <input type="text" id="gcash_number" name="gcash_number" class="form-control" required><br>
            </div>
        </div><br>
        <input type="submit" value="Register" name="submit" class="btn">
    </form>
</body>
</html>



<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">

<style>
    h2 {
        text-align: center;
        color: #F3EEEA;
        font-weight: bold;
        font-size: 6rem;
        font-family: 'Satisfy';
    }

    body {
        background-image: url('../uploads/b.jpg');
        background-size: cover;
        background-attachment: fixed;
        height: 100vh;
        margin-top: 30px;
        margin-left: 10px;
    }

    .form-outline {
        max-width: 1000px; /* Adjust the max-width of the form */
        margin: 0 auto; /* Center the form horizontally */
        padding: 40px;
        background-color: rgba(255, 255, 255, 0.7); /* Adjust the background color and opacity */
        border-radius: 10px;
    }

    .form-outline label {
        font-size: 1.2rem; /* Adjust the font size of labels */
        font-weight: bold;
        color: #333; /* Adjust the color of labels */
    }

    .form-control {
        padding: 10px;
        margin-top: 10px;
        border-radius: 5px;
        border: 1px solid #ccc; /* Adjust the border color */
        width: 100%; /* Adjust the width of form controls */
    }

    .form-control:focus {
        outline: none;
        border-color: #B0A695; /* Adjust the border color on focus */
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #4F709C;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        display: block;
        margin: 30px auto; /* Center the button horizontally */
        width: 300px;
    }

    .btn:hover {
        background-color: #9B8E7B; /* Adjust the background color on hover */
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .form-group {
        flex: 0 0 calc(50% - 20px); /* Two columns with a gap of 20px */
        margin-right: 40px;
    }

    .form-group:last-child {
        margin-right: 0; /* Remove right margin for the last column */
    }
</style>
