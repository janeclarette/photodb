
    
<body>
    <section class="background">
    <?php
session_start();
include("../include/config.php"); // Include your database connection
include("../photographer/header.php");

// Check if the photographer is logged in
if (!isset($_SESSION['PhotographerID'])) {
    header("Location: ../photodb/admin/login.php");
    exit();
}

$photographer_id = $_SESSION['PhotographerID'];

$addedDates = [];

// Assuming you have a database connection named $conn
// Delete past schedules when the page is loaded
$sqlDeletePastSchedules = "DELETE av, at
                           FROM availability_schedule AS av
                           JOIN availability_time AS at ON av.scheduleid = at.scheduleid
                           JOIN available_date ad ON av.date_id = ad.date_id
                           WHERE av.photographerID = '$photographer_id'
                           AND ad.avail_date < CURDATE()";

mysqli_query($conn, $sqlDeletePastSchedules);

// Fetch remaining schedules
$sqlFetchAddedDates = "SELECT av.*, t.start_time, t.end_time, t.session_type, s.status_name AS schedule_status, ad.avail_date
    FROM availability_schedule AS av
    JOIN availability_time AS at ON av.scheduleid = at.scheduleid
    JOIN time AS t ON at.time_id = t.time_id
    LEFT JOIN sched_status s ON av.schedule_status_id = s.schedule_status_id
    JOIN available_date ad ON av.date_id = ad.date_id
    WHERE av.photographerID = '$photographer_id'
    ORDER BY av.date_id";

$resultFetchAddedDates = mysqli_query($conn, $sqlFetchAddedDates);
if ($resultFetchAddedDates) {
    while ($row = mysqli_fetch_assoc($resultFetchAddedDates)) {
        $avail_date = $row['avail_date'];
        $start_time = $row['start_time'];
        $end_time = $row['end_time'];
        $session_type = $row['session_type'];
        $schedule_status_id = $row['schedule_status_id'];

        // Combine date and time to create a DateTime object
        $startDateTime = new DateTime($avail_date . ' ' . $start_time);
        $endDateTime = new DateTime($avail_date . ' ' . $end_time);

        $addedDates[] = [
            'title' => $session_type,
            'start' => $startDateTime->format('Y-m-d H:i:s'), // Format with both date and time
            'end' => $endDateTime->format('Y-m-d H:i:s'), // Format with both date and time
            'color' => $schedule_status_id == 1 ? '#4CAF50' : '#FF5722',
        ];
    }
}
    // Handle form submission
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve selected date and times
    $selectedDate = $_POST['selectedDateInput'];
    $selectedTimes = $_POST['selected_times'];

    // Check if the selected date is in the past
    $today = new DateTime();
    $selectedDateTime = new DateTime($selectedDate);
    if ($selectedDateTime < $today) {
        echo '<script>';
        echo 'alert("Cannot select a past date!");';
        echo 'window.location.href = "schedule.php";'; // Redirect to the desired page
        echo '</script>';
        exit();
    }

    // Insert into the database
    $sqlInsertDate = "INSERT INTO available_date (avail_date) VALUES ('$selectedDate')";
    if (mysqli_query($conn, $sqlInsertDate)) {
        $dateID = mysqli_insert_id($conn);

        // Loop through selected times to add a new schedule for each time
        foreach ($selectedTimes as $time_id) {
            $sqlInsertSchedule = "INSERT INTO availability_schedule (PhotographerID, date_id, schedule_status_id) 
                                  VALUES ('$photographer_id', '$dateID', 1)"; // Assuming schedule_status_id 1 represents 'Available'
            mysqli_query($conn, $sqlInsertSchedule);
            $scheduleID = mysqli_insert_id($conn); // Get the ID of the inserted schedule

            // Insert the selected time into availability_time
            $sqlInsertTime = "INSERT INTO availability_time (scheduleid, time_id) 
                              VALUES ('$scheduleID', '$time_id')";
            mysqli_query($conn, $sqlInsertTime);
        }

        echo '<script>';
        echo 'alert("Availability added successfully!");';
        echo 'window.location.href = "schedule.php";'; // Redirect to the desired page
        echo '</script>';
        exit();
    } else {
        echo "Error: " . $sqlInsertDate . "<br>" . mysqli_error($conn);
    }
}

    ?>

    <div id="availability-calendar-container">
        <div id="availability-calendar"></div>

        <div id="popupOverlay"></div>
        <div id="popupContainer">
            <form method="post" action="">
                <h2>Select Time for <span id="selectedDate"></span></h2>
                <?php
                $sql_fetch_times = "SELECT * FROM time";
                $result_times = mysqli_query($conn, $sql_fetch_times);

                if ($result_times) {
                    while ($row = mysqli_fetch_assoc($result_times)) {
                        $time_id = $row['time_id'];
                        $start_time = $row['start_time'];
                        $end_time = $row['end_time'];
                        $session_type = $row['session_type'];

                        echo "<input type='checkbox' id='time_{$time_id}' name='selected_times[]' value='{$time_id}'>";
                        echo "<label for='time_{$time_id}'>{$start_time} - {$end_time} ({$session_type})</label><br>";
                    }
                }
                ?>
                <input type="hidden" id="selectedDateInput" name="selectedDateInput" value="">
                <button type="submit" id="submitTime">Submit</button>
                <button type="button" id="cancelButton">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var addedDates = <?php echo json_encode($addedDates); ?>;
            var selectedDate; // To store the selected date

            $('#availability-calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: addedDates,
                dayClick: function (date, jsEvent, view) {
                    selectedDate = date.format();
                    // Show the overlay and popup form when a date is clicked
                    $('#selectedDate').text(selectedDate);
                    $('#selectedDateInput').val(selectedDate); // Set the selected date in the hidden input
                    $('#popupOverlay').show();
                    $('#popupContainer').show();
                }
            });

            $('#submitTime').on('click', function () {
                // Handle the submission of the time selection form here
                // You can send an AJAX request to add the selected time for the selected date
                console.log('Selected Date:', selectedDate);
                // Close the overlay and popup form
                $('#popupOverlay').hide();
                $('#popupContainer').hide();
            });

            $('#cancelButton').on('click', function () {
                // Close the overlay and popup form when Cancel button is clicked
                $('#popupOverlay').hide();
                $('#popupContainer').hide();
            });
        });
    </script>
</body>

</html>

    
    
    
    <style>
    
    #availability-calendar-container {
    margin-top: 50px;
    position: relative;
    max-width: 80%;
    margin-left: 180px;
    background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent white background */
    border: 2px solid rgba(255, 255, 255, 10);
    backdrop-filter: blur(10px); /* Apply a blur effect behind the container */
    padding: 30px 40px;
}
    body, h1, h2, h3, h4, h5, h6, p, ul, ol, li, figure, figcaption, blockquote, dl, dd, dt {
                margin: 0;
                padding: 0;
                font-family: 'serif';
            }
        #availability-calendar-container {
            margin-top: 50px;
            position: relative;
            max-width: 80%;
            margin-left: 180px;
        }

        #popupOverlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        #popupContainer {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1001;
            border-radius: 8px;
        }

        #cancelButton {
            background-color: #d9534f;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }

        #cancelButton:hover {
            background-color: #c9302c;
        }

        #submitTime {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        #submitTime:hover {
            background-color: #45a049;
        }
        .background {
                background-image: url('../uploads/cover.jpg');  
    background-size: cover;
    background-position: center bottom;
    opacity: 0.9;  /* Adjust the opacity to make the image less visible */
        }
    </style>
</head>



    <title>Your Photographer Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.css" />
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.js"></script>
     