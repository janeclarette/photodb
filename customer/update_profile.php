<?php
ob_start(); //
session_start();
include("../include/config.php");
include("../customer/header.php");

// Check if the customer is logged in
if (!isset($_SESSION['CustomerID'])) {
    $_SESSION['message'] = "You must be logged in to view your profile. <a href='../admin/login.php'>Click here to log in</a>";
    header("Location: /lib2/customer/customerdashboard.php");
    exit();
}

// Get the customer ID from the session
$customerID = $_SESSION['CustomerID'];

// Fetch customer information including city details
$sql = "SELECT c.Name, c.Phone_Number, c.Gcash_Number,c.Address, ct.CityName AS City, c.Email, c.img_customer 
        FROM customers c
        LEFT JOIN cities ct ON c.CityID = ct.CityID
        WHERE c.CustomerID = $customerID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output customer profile
    $row = $result->fetch_assoc();
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
    if ($_FILES['profile_image']['error'] == 0) {
        $profile_image = $_FILES['profile_image']['name'];
        $temp_name = $_FILES['profile_image']['tmp_name'];
        $file_destination = "../uploads/" . $profile_image;

        // Move the uploaded file to the specified destination
        move_uploaded_file($temp_name, $file_destination);

        // Update the customer's profile picture in the database
        $updatePictureQuery = "UPDATE customers SET img_customer = '$file_destination' WHERE CustomerID = $customerID";
        $conn->query($updatePictureQuery);
    }

    // Update the customer's information in the database
    $updateQuery = "UPDATE customers SET Name = '$name', Email = '$email', Phone_Number = '$phone_number', Gcash_Number = '$gcash_number',Address = '$address', CityID = $cityID WHERE CustomerID = $customerID";
    $conn->query($updateQuery);

    // Redirect back to profile.php
    header("Location: profile.php");
    exit();
}
?>

<!-- HTML code to display the profile form -->
<h2> Customer Profile </h2>

<div class="container">
    <div class="profile">
        <div class="profile-image">
            <?php
            if ($row['img_customer']) {
                echo "<img src='{$row['img_customer']}' alt='Profile Image'>";
            } else {
                echo "<p>No image available</p>";
            }
            ?>
        </div>
        <div class="profile-details">
            <form method="post" enctype="multipart/form-data">
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
                    <input type="text" name="address" value="<?php echo $row['Address']; ?>" required>
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
                    <span class="label">Profile Image:</span>
                    <input type="file" name="profile_image">
                </div> 
                <button type="submit" class="edit-profile">Save Changes</button>
            </form>
        </div>
    </div>
</div>
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
    height: auto; /* Allow height to adjust based on content */
    background-color: #ffffff;
    padding: 20px; /* Add padding for spacing */
    border-radius: 10px; /* Add some border radius for a rounded look */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add a subtle shadow for depth */
}

.profile {
    display: flex;
    flex-wrap: wrap; /* Allow flex items to wrap */
    margin-left: 50px;
    margin-top: 20px;
}

.profile-image {
    margin-right: 20px;
    flex: 1; /* Take 1/3 of the space */
}

.profile-image img {
    width: 100%;
    height: auto;
}

.profile-details {
    flex: 2; /* Take 2/3 of the space */
    display: flex;
    flex-direction: column;
}

.profile-details {
    flex: 1; /* Take remaining space */
    display: flex;
    flex-direction: column;
    margin-left: 30px;
}

.profile-details .detail {
    margin-bottom: 20px;
    margin-top: 20px;
    margin-left: 40px;
    display: block;
}

.profile-details .label {
    font-weight: bold;
    display: block;
    margin-bottom: 10px;
}

button[type="submit"] {
    padding: 8px 16px;
    background-color: #4F709C;
    color: #ffffff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 20px; /* Adjust top margin as needed */
}

button[type="submit"]:hover {
    background-color: #375d83;
}

</style>
