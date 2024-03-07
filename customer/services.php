<?php
session_start(); // Start the session
include("../include/config.php");
include("../customer/header.php");
?>
<div class="search-container">
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit">Search</button>
    </form>
</div>

<?php
if (!empty($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT w.Photos, p.Name AS PhotographerName, w.Album, w.Description, s.TypeName, w.ServiceTypeID
    FROM works w
    JOIN photographers p ON w.PhotographerID = p.PhotographerID
    JOIN servicetypes s ON w.ServiceTypeID = s.ServiceTypeID
    WHERE p.Name LIKE '%$search%'
    OR w.Album LIKE '%$search%'
    OR w.Description LIKE '%$search%'
    OR s.TypeName LIKE '%$search%'";
} else {
    $query = "SELECT w.Photos, p.Name AS PhotographerName, w.Album, w.Description, s.TypeName, w.ServiceTypeID
    FROM works w
    JOIN photographers p ON w.PhotographerID = p.PhotographerID
    JOIN servicetypes s ON w.ServiceTypeID = s.ServiceTypeID";
}

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo '<div class="works-container">';
    while ($row = $result->fetch_assoc()) {
        // Display each work
    }
    echo '</div>';
} else {
    echo '<p>No results found.</p>';
}
?>

<script>
    function changeSlide(containerIndex) {
        let currentSlide = 0;
        const workContainers = document.querySelectorAll('.work-container');

        function showNextSlide() {
            const slides = workContainers[containerIndex].querySelectorAll('.work img');
            slides[currentSlide].style.display = 'none';
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].style.display = 'block';
        }

        showNextSlide();
        setInterval(showNextSlide, 5000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const workContainers = document.querySelectorAll('.work-container');

        workContainers.forEach((container, index) => {
            changeSlide(index);
        });
    });
</script>

<style>
    body {
        background-color: #E0F4FF;
    }

    .search-container {
        margin-top: 30px;
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        z-index: 999; /* Ensure it overlays other elements */
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
    }

    .search-container button {
        padding: 5px 10px;
        background-color: #4F709C;
        border: none;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
    }

    .search-container button:hover {
        background-color: #0056b3;
    }

    .works-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        margin-top: 50px;
    }

    .work-container {
        width: calc(33.33% - 20px);
        margin: 10px;
        max-width: 350px;
        box-sizing: border-box;
    }

    @media (max-width: 767px) {
        .work-container {
            width: calc(50% - 20px);
            max-width: 350px;
        }
    }

    @media (min-width: 768px) and (max-width: 1023px) {
        .work-container {
            width: calc(33.33% - 20px);
            max-width: 350px;
        }
    }

    @media (min-width: 1024px) {
        .work-container {
            width: calc(33.33% - 20px);
            max-width: 450px;
        }
    }

    .work {
        width: 450px;
        text-align: center;
        padding: 10px;
        background-color: #fff;
        height: 420px;
        border-radius: 10px;
    }

    .work img {
        width: 100%;
        height: 350px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        max-width: 90%;
        margin-top: 15px;
        margin-left: 20px;
        display: block;
    }

    .work-id-container {
        background-color: #213555;
        color: #fff;
        padding: 10px;
        border-bottom-left-radius: 15px;
        border-bottom-right-radius: 15px;
        width: 450px;
        text-align: center;
    }

    .work-container:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
    }

    h2 {
        text-align: center;
        font-size: 2rem;
        font-family: 'Satisfy';
        color: #333;
        margin-top: 10px;
        margin-bottom: 20px;
    }

    p {
        text-align: center;
        font-size: 1.5rem;
        font-family: serif;
        color: #000000;
        margin-bottom: 10px;
    }
</style>

<?php include("../include/footer.php"); ?>
