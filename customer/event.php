<?php
session_start(); // Start the session
include("../include/config.php");
include("../customer/header.php");

    $selectedServiceTypeID = '3';
    $selectedTypeName = 'Event Photography';

    $worksSql = "SELECT w.Photos, w.Album, p.Name, w.WorkID 
                 FROM works w
                 JOIN photographers p ON w.PhotographerID = p.PhotographerID
                 JOIN servicetypes st ON w.ServiceTypeID = st.ServiceTypeID
                 WHERE st.ServiceTypeID = $selectedServiceTypeID
                 AND st.TypeName = '$selectedTypeName'";
    $worksResult = $conn->query($worksSql);

    if ($worksResult->num_rows > 0) {
        echo '<div class="works-container">';
        while ($workRow = $worksResult->fetch_assoc()) {
            echo '<div class="work-container">'; 

            echo '<div class="work">';

            $photosArray = explode(',', $workRow['Photos']);

            foreach ($photosArray as $photo) {
                echo '<img src="' . $photo . '" alt="Work Image">';
            }

            echo '<h2>Album Title: ' . $workRow['Album'] . '</h2>';
            echo '</div>'; 
            echo '<div class="work-id-container">';
            echo '<p>Photographer Name: ' . $workRow['Name'] . '</p>';
            echo '</div>';

            echo '</div>';
        }

        echo '</div>';
    } else {
        echo '<p>No works found for the specified service type and typename.</p>';
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
            margin-top:15px;
            margin-left:20px;
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
        .work img:not(:first-child) {
        display: none;
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
            font-family:  serif;
            color: #333;
            margin-bottom: 10px;
        }
</style>
<?php include("../include/footer.php"); ?>