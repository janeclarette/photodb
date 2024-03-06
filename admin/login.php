<?php

session_start();
include("../include/config.php");

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);

    // Check if the user exists in the Admin table
    $sql = "SELECT * FROM Admin WHERE Username='$username' AND Password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Admin found, set session and redirect
        $admindata = mysqli_fetch_assoc($result);
        $_SESSION['AdminID'] = $admindata['AdminID'];
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'admin';
        header("Location: ../admin/admindashboard.php");
        exit();
    }

    // Check if the user exists in the Customers table
    $sql = "SELECT CustomerID, Username FROM Customers WHERE Username='$username' AND Password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Customer found, set session and redirect
        $row = mysqli_fetch_assoc($result);
        $_SESSION['CustomerID'] = $row['CustomerID'];
        $_SESSION['username'] = $row['Username'];
        $_SESSION['role'] = 'customer';
        header("Location: ../customer/customerdashboard.php");
        exit();
    } else {
        $_SESSION['message'] = 'Invalid username or password.';
    
    }

    // Check if the user exists in the Photographers table
    $sql = "SELECT * FROM Photographers WHERE Username='$username' AND Password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $photographerData = mysqli_fetch_assoc($result);
        $_SESSION['PhotographerID'] = $photographerData['PhotographerID'];
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'photographer';
        header("Location: ../photographer/phdashboard.php");
        echo "PhotographerID set in session: " . $_SESSION['PhotographerID'];
        exit();
    }
    $_SESSION['message'] = 'Invalid username or password.';
}

// Display any session messages
if (isset($_SESSION['message'])) {
    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
    <strong>{$_SESSION['message']}</strong>
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    unset($_SESSION['message']);
}
?>

<h2>Login</h2>


<div class="row col-md-6 mx-auto">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <!-- Username input -->
        <div class="form-outline mb-4">
            <i class="fas fa-user"></i><input type="text" id="form2Example1" class="form-control" name="username" placeholder="Username" />
        </div>

        <!-- Password input -->
        <div class="form-outline mb-4">
            <i class="fas fa-lock"></i> <!-- Replace "fas fa-lock" with the appropriate icon class for a lock or key icon -->
            <input type="password" id="form2Example2" class="form-control" name="password" placeholder="Password" />
        </div>

        <!-- Submit button -->
        <input type="submit" class="btn btn-block mb-4" name="submit" value="Sign in">


    </form>
</div>



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Satisfy&display=swap" rel="stylesheet">
    <body>
<style>

    p {
        color: white;
    }
    h2 {
        padding-top: 70px;
        text-align: center;
        color: #F3EEEA;
        font-weight: bold;
        font-size: 6rem;    
        font-family: 'Satisfy';
    }
    .log{ 
        background-color: #EBE3D5;
        color: #555;
    }
    body {
        background-image: url('../uploads/a.jpg'); /* Replace 'path/to/your/background-image.jpg' with the actual path to your image */
        background-size: cover;
        background-position: center; /* Lower the background image */
        background-attachment: fixed;
        height: 100vh;
        margin-top: 30px;
        margin-left: 10px;
    }

    .form-outline i {
    position: absolute;
    top: 79%;
    transform: translateY(-50%);
    left: 20px;

    }

    /* Adjust the height of the input fields */
.form-outline input {
    height: 40px; /* Adjust the height */
    border-radius: 20px;
}


    .form-outline .form-control {
            padding-left: 50px;
            margin-top: 50px;
            width: 100%;
    }

    .form-outline {
            padding-top: 5px;
            position: relative;
            max-width: 600px; 
            margin: 0 auto; 
            text-align: center; 
        }


    .btn {
        border-radius: 10px;
        background-color: #9BABB8;
        color: white;
        font-size: 1.2rem;
        display: block; 
        margin: 0 auto; 
        margin-top: 50px;
        width: 400px;
        height: 40px;
    }

    .btn:hover{
        border-radius: 70px;
        background-color: #4F709C;

    }
</style>