<?php
ob_start(); //
session_start();
include("../include/config.php");
include("../photographer/header.php");

// Check if the photographer ID is provided in the URL
if (!isset($_SESSION['PhotographerID'])) {
    // Redirect to login page if not logged in
    header("Location: /photodb/photographer/login.php");
    exit();
}

// Get the photographer ID from the session
$photographerID = $_SESSION['PhotographerID'];

// Fetch photographer information including city name by joining with cities table
$sql = "SELECT p.Name, p.Phone_Number, p.Gcash_Number, p.Address, c.CityName AS City, p.Email, p.img_photographer 
        FROM photographers p
        LEFT JOIN cities c ON p.CityID = c.CityID
        WHERE p.PhotographerID = $photographerID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output photographer profile
    $row = $result->fetch_assoc();
} else {
    echo "No results found.";
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $gcash_number = $_POST['gcash_number'];
    $address = $_POST['address'];
    $cityID = $_POST['city'];

    // Check if a new profile picture is uploaded
    if ($_FILES['profile_picture']['error'] == 0) {
        $profile_picture = $_FILES['profile_picture']['name'];
        $temp_name = $_FILES['profile_picture']['tmp_name'];
        $file_destination = "../uploads/" . $profile_picture;

        // Move the uploaded file to the specified destination
        move_uploaded_file($temp_name, $file_destination);

        // Update the photographer's profile picture in the database
        $updatePictureQuery = "UPDATE photographers SET img_photographer = '$file_destination' WHERE PhotographerID = $photographerID";
        $conn->query($updatePictureQuery);
    }

    // Update the photographer's information in the database
    $updateQuery = "UPDATE photographers SET Name = '$name', Email = '$email', Phone_Number = '$phone_number', Gcash_Number = '$gcash_number' ,Address = '$address', CityID = $cityID WHERE PhotographerID = $photographerID";
    $conn->query($updateQuery);

    // Redirect back to phprofile.php
    header("Location: phprofile.php");
    exit();
}
?>

<body>
    <h2>Edit Photographer Profile</h2>

    <div class="container">
        <div class="profile">
            <div class="profile-image">
                <?php
                if ($row['img_photographer']) {
                    echo "<img src='{$row['img_photographer']}' alt='Profile Image'>";
                } else {
                    echo "<p>No image available</p>";
                }
                ?>
            </div>
            <div class="profile-details">
                <form method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="photographerID" value="<?php echo $photographerID; ?>">

                    <div class="detail">
                        <span class="label">Name:</span>
                        <input type="text" name="name" value="<?php echo $row['Name']; ?>" required>
                    </div>
                    <div class="detail">
                        <span class="label">Email:</span>
                        <input type="email" name="email" value="<?php echo $row['Email']; ?>" required>
                    </div>
                    <div class="detail">
                        <span class="label">Phone Number:</span>
                        <input type="text" name="phone_number" value="<?php echo $row['Phone_Number']; ?>" required>
                    </div>
                    <div class="detail">
                        <span class="label">Gcash Number:</span>
                        <input type="text" name="gcash_number" value="<?php echo $row['Gcash_Number']; ?>" required>
                    </div>
                    <div class="detail">
                        <span class="label">Address:</span>
                        <textarea name="address" required><?php echo $row['Address']; ?></textarea>
                    </div>
                    <div class="detail">
                        <span class="label">City:</span>
                        <select name="city" required>
                            <option value="" disabled>Select your City</option>
                            <?php
                            $cityQuery = "SELECT CityID, CityName FROM Cities";
                            $result = $conn->query($cityQuery);

                            if ($result->num_rows > 0) {
                                while ($cityRow = $result->fetch_assoc()) {
                                    $selected = ($row['City'] == $cityRow["CityName"]) ? "selected" : "";
                                    echo '<option value="' . $cityRow["CityID"] . '" ' . $selected . '>' . $cityRow["CityName"] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="detail">
                        <span class="label">Profile Picture:</span>
                        <input type="file" name="profile_picture">
                    </div>
                    <button type="submit" class="edit-profile">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>


<style>
    h2 {
        margin-top: 30px;
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
        font-family: serif;
    }

    .container {
        max-width: 700px;
        margin: 50px auto;
        margin-bottom: 30px;
        height: 650px; /* Allow height to adjust based on content */
        background-color: #ffffff;
        padding: 20px; /* Add padding for spacing */
        border-radius: 10px; /* Add some border radius for a rounded look */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add a subtle shadow for depth */
    }

    .profile {
        display: flex;
        flex-wrap: wrap; /* Allow flex items to wrap */
        margin-left: 60px;
        margin-top: 20px;
    }

    .profile-image {
        margin-top: 100px;
        margin-bottom: 200px;
        height: 200px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 20px;
    }

    .profile-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-details {
        flex: 1; /* Take remaining space */
        display: flex;
        flex-direction: column;
        margin-left: 50px;
    }

    .profile-details .detail {
        margin-bottom: 20px;
        margin-top: 20px;
        margin-left: 50px;
        display: block;
    }

    .profile-details .label {
        font-weight: bold;
        display: block;
        margin-bottom: 10px;
    }

    .profile-details .value {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
        width: 200px;
        display: block;
        margin-bottom: 10px;
    }
        .edit-profile {
        padding: 8px 16px;
        background-color: #4F709C;
        color: #ffffff;
        border-radius: 5px;
        width:100px;
        text-decoration: none;
        margin-left: 80px;
        text-align: center;
        }

        .edit-profile:hover {
            background-color: #375d83;
        }

    </style>


