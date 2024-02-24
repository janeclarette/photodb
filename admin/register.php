<?php
session_start();
include("../include/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $role = isset($_POST['data-role']) ? $_POST['data-role'] : '';

    if (empty($role)) {
        echo "Error: Role not selected.";
        exit();
    }

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);
    $cityID = $_POST['city'];

    if ($role == 'Photographer') {
        $img_photographer = $_POST['img_photographer'];
        $sql = "INSERT INTO Photographers (Name, Phone_Number, Address, Email, Username, Password, img_photographer, CityID)
                VALUES ('$name', '$phone', '$address', '$email', '$username', '$password', '$img_photographer', '$cityID')";
    } elseif ($role == 'Customer') {
        $img_customer = $_POST['img_customer'];
        $sql = "INSERT INTO Customers (Name, Phone_Number, Address, Email, Username, Password, img_customer, CityID)
                VALUES ('$name', '$phone', '$address', '$email', '$username', '$password', '$img_customer', '$cityID')";
    }

    if ($sql !== "") {
        if (mysqli_query($conn, $sql)) {
            echo '<script>';
            echo 'if(confirm("Registration successful. Do you want to proceed to login?"))';
            echo '  window.location.href = "login.php";';
            echo '</script>';
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}    

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        body {
            background-image: url('../uploads/a.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
        }

        .registration-form {
            background-color: #EBE3D5;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        select,
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .role-selection {
            margin-bottom: 20px;
        }

        .role-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        .role-buttons {
            display: flex;
            gap: 10px;
        }

        .role-button {
            flex: 1;
            padding: 10px;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #9BABB8;
            color: white;
            font-weight: bold;
        }

        .role-button:hover {
            background-color: #4F709C;
        }

        .submit-button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #4F709C;
            color: white;
            font-weight: bold;
        }

        .submit-button:hover {
            background-color: #2F4E6E;
        }
    </style>
</head>

<body>
    <div class="row col-md-6 mx-auto">
        <div class="registration-form">
            <div class="role-selection">
                <span class="role-label">Select Role:</span>
                <div class="role-buttons">
                    <button class="role-button" onclick="selectRole('Photographer')">Photographer</button>
                    <button class="role-button" onclick="selectRole('Customer')">Customer</button>
                </div>
            </div>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="registrationForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" placeholder="Your Name" />
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" name="phone" placeholder="Phone Number" />
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" name="address" placeholder="Your Address" />
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" placeholder="Your Email" />
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" placeholder="Your Username" />
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" placeholder="Your Password" />
                </div>

                <div class="form-group">
                    <label for="city">City:</label>
                    <select name="city" required>
                        <option value="" disabled selected>Select your city</option>
                        <?php
                            $query = "SELECT CityID, CityName FROM cities";
                            $result = mysqli_query($conn, $query);

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<option value="' . $row['CityID'] . '">' . $row['CityName'] . '</option>';
                            }
                        ?>
                    </select>
                </div>

               <div id="photographerFields" style="display:none" class="form-group">
                    <label for="img_photographer">Photographer Image:</label>
                    <input type="file" name="images[]" multiple accept="image/*" />
                </div>

                <div id="customerFields" style="display:none" class="form-group">
                    <label for="img_customer">Customer Image:</label>
                    <input type="file" name="images[]" multiple accept="image/*" />
                </div>

                <input type="hidden" name="data-role" id="roleInput" value="">

                <button type="submit" class="submit-button" name="submit">Register</button>
            </form>
        </div>
    </div>

    <script>
        function selectRole(role) {
            var photographerFields = document.getElementById('photographerFields');
            var customerFields = document.getElementById('customerFields');

            if (role === 'Photographer') {
                photographerFields.style.display = 'block';
                customerFields.style.display = 'none';
            } else if (role === 'Customer') {
                photographerFields.style.display = 'none';
                customerFields.style.display = 'block';
            }

            document.getElementById('roleInput').value = role;
        }
    </script>
</body>

</html>
