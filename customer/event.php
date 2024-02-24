<?php
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

            echo '<h3>Album Title: ' . $workRow['Album'] . '</h3>';
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

        .works-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around; 
            margin: 0 -10px; 
            
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
            width: 100%;
            text-align: center;
            padding: 20px;
            background-color: #E9E4D4;
            height: 400px;
        }

        .work img {
        width: 100%;
        height: 350px; 
        object-fit: cover; 
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        max-width: 90%; 
        margin: 0 auto; 
        display: block;
        
        }
        .work h3 {
            font-size: 1.5rem;
            margin-top: 10px;
        }

        .work p {
            font-size: 1rem;
            color: #777;
        }

        .work-id-container {
            background-color: #4F709C;
            color: #fff;
            padding: 10px;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
            width: 470px;
            text-align: center;
            
        }

        .work-container:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
        .work img:not(:first-child) {
            display: none;
        }
   
    </style>