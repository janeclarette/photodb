<?php
session_start(); // Start the session
// Include necessary files and establish a database connection
include("../include/config.php");
include("../customer/header.php");  // Include your database connection
?>
<div class="search-container">
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit">Search</button>
    </form>
</div>
<!-- Main content of the page -->

<section class="services">
    <section class="background">
        <h4> Photographers</h4>

        <p>Meet our talented photographers.</p>
    </section>
    <div class="photographer-container">
        <?php
        // Fetch photographers from the database based on search query
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $photographersSql = "SELECT * FROM Photographers WHERE Name LIKE '%$search%'";
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
                echo '<button type="submit" style="background-color: rgba(75, 192, 192, 20); color: #fff; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer;">View Albums</button>';
                echo '</form>';
                echo '<form action="bookp.php" method="GET" style="display: inline;">';
                echo '<input type="hidden" name="photographer_id" value="' . $photographer['PhotographerID'] . '">';
                echo '<button type="submit" style="background-color: rgba(75, 192, 192, 20); color: #fff; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">Book Photographer</button>';
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
   body{
                background-image: url('../uploads/cover.jpg');  
    background-size: cover;
    background-position: center bottom;
    opacity: 0.9;  /* Adjust the opacity to make the image less visible */
        }
        h4 {
            margin-top: 20px;
            text-align: center;
            font-size: 7rem;
            color: #333;
            font-family: 'Satisfy';
            margin-bottom: 10px;
        }

    .photographer-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        animation: fadeInUp 1s ease-out; /* Add fade-in animation */
        margin-top: 50px;
        height: 400px; 
    }

    .search-container {
    margin-top: 30px;
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1; /* Ensure it overlays other elements */
}



    .search-container form {
        display: inline-block;
        padding: 10px;
        border-radius: 5px;

    }

    .search-container input[type="text"] {
            padding: 10px;
            border: none;
            border-radius: 10px;
            margin-right: 10px;
            width: 300px;
            background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent white background */
    border: 2px solid rgba(255,255,255, 10);
    backdrop-filter: blur(10px);
    }

    .search-container button {
        padding: 5px 10px;
        background-color: rgba(75, 192, 192, 20);
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
    }

    .search-container button:hover {
        background-color: #0056b3;
    }

    .photographer {
        border-radius: 10px;
        padding: 20px;
        margin: 20px;
        text-align: center;
        box-shadow: 0px 4px 8px rgba(0, 0, 0.1, 0.1);
        transition: box-shadow 0.5s ease-in-out, background-color 0.5s ease-in-out; /* Include transition for background-color */
        background-color: rgba(0, 0, 0, 0.2);
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
        background-color: rgba(0, 0, 0, 0.2);
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease-in-out;
    }

    .album-card:hover {
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    }

    p {
        text-align: center;
        font-size: 1.5rem;
        font-family: serif;
        color: #333;
        margin-bottom: 15px;
    }
</style>

<?php include("../include/footer.php"); ?>
