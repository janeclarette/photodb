<?php
session_start();
include("../include/config.php");
include("../photographer/header.php");

if (!isset($_SESSION['PhotographerID'])) {
    header("Location: ../photodb/admin/login.php");
    exit();
}

$photographer_id = $_SESSION['PhotographerID'];
function deleteOldDates() {
    global $conn;

    $currentDate = date('Y-m-d');
    $sql = "DELETE av, at, ad
            FROM availability_schedule av
            JOIN availability_time at ON av.scheduleid = at.scheduleid
            JOIN available_date ad ON av.date_id = ad.date_id
            WHERE ad.avail_date < '$currentDate'";

    mysqli_query($conn, $sql);
}
deleteOldDates();

function retrieveDateId($avail_date) {
    global $conn;

    $sql = "SELECT date_id FROM available_date WHERE avail_date = '$avail_date'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['date_id'];
    } else {
        $sqlInsertDate = "INSERT INTO available_date (avail_date) VALUES ('$avail_date')";
        if (mysqli_query($conn, $sqlInsertDate)) {
            return mysqli_insert_id($conn);
        } else {
            echo "Error: " . $sqlInsertDate . "<br>" . mysqli_error($conn);
            return false;
        }
    }
}

function insertAvailabilityData($avail_date, $time_id, $is_weekday, $is_weekend, $schedule_status_id) {
    global $conn, $photographer_id;

    $date_id = retrieveDateId($avail_date);

    $sql = "INSERT INTO availability_schedule (photographerID, date_id, is_weekday, is_weekend, schedule_status_id)
            VALUES ('$photographer_id', '$date_id', '$is_weekday', '$is_weekend', '$schedule_status_id')";

    if (mysqli_query($conn, $sql)) {
        $schedule_id = mysqli_insert_id($conn);

        $sql = "INSERT INTO availability_time (scheduleid, time_id) VALUES ('$schedule_id', '$time_id')";

        if (mysqli_query($conn, $sql)) {
            $sqlCheckTime = "SELECT * FROM time WHERE time_id = '$time_id'";
            $resultCheckTime = mysqli_query($conn, $sqlCheckTime);

            if (mysqli_num_rows($resultCheckTime) == 0) {
                $sqlFetchTimeData = "SELECT * FROM time WHERE time_id = '$time_id'";
                $resultFetchTimeData = mysqli_query($conn, $sqlFetchTimeData);

                if ($resultFetchTimeData) {
                    $timeData = mysqli_fetch_assoc($resultFetchTimeData);

                    $sqlInsertTime = "INSERT INTO time (time_id, start_time, end_time, session_duration, session_type)
                                      VALUES ('$time_id', '{$timeData['start_time']}', '{$timeData['end_time']}', '{$timeData['session_duration']}', '{$timeData['session_type']}')";

                    mysqli_query($conn, $sqlInsertTime);
                }
            }
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $availability_month = $_POST["availability_month"];
    $is_weekday = isset($_POST["is_weekday"]) ? 1 : 0;
    $is_weekend = isset($_POST["is_weekend"]) ? 1 : 0;
    $specific_date = isset($_POST["specific_date"]) ? $_POST["specific_date"] : null;
    $schedule_status_id = 1;

    if (!empty($specific_date)) {
        $availability_date = date('Y-m-d', strtotime($specific_date));
        foreach ($_POST['selected_times'] as $time_id) {
            insertAvailabilityData($availability_date, $time_id, $is_weekday, $is_weekend, $schedule_status_id);
        }

        echo '<script>';
        echo 'alert("Date and Times were added successfully");';
        echo 'window.location.href = "schedule.php";';
        echo '</script>';
    } else {
        $start_date = new DateTime($availability_month . '-01');
        $end_date = new DateTime($start_date->format('Y-m-t'));
        $current_date = clone $start_date;

        while ($current_date <= $end_date) {
            if (($is_weekday && $current_date->format('N') < 6) || ($is_weekend && $current_date->format('N') >= 6)) {
                $availability_date = $current_date->format('Y-m-d');
                foreach ($_POST['selected_times'] as $time_id) {
                    insertAvailabilityData($availability_date, $time_id, $is_weekday, $is_weekend, $schedule_status_id);
                }
            }

            $current_date->modify('+1 day');
        }

        echo '<script>';
        echo 'alert("Dates and Times were added successfully");';
        echo 'window.location.href = "schedule.php";';
        echo '</script>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Your Photographer Page</title>
</head>

<body>
<?php

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
        $day = date('l', strtotime($avail_date)); 
        $start_time = $row['start_time'];
        $end_time = $row['end_time'];
        $session_type = $row['session_type'];
        $schedule_status = $row['schedule_status']; 
        $schedule_status_id = $row['schedule_status_id']; 

        $added_dates[$avail_date] = [
            'day' => $day,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'session_type' => $session_type,
            'schedule_status' => $schedule_status,
            'schedule_status_id' => $schedule_status_id,
        ];
    }
}
?>

    <div class="container">
        <h2>Manage Availability Schedule</h2>

        <form method="post" action="">

            <label for="availability_month">Select Month:</label>
            <input type="month" id="availability_month" name="availability_month">

            <label for="is_weekday">Available on weekdays:</label>
            <input type="checkbox" id="is_weekday" name="is_weekday" value="1">

            <label for="is_weekend">Available on weekends:</label>
            <input type="checkbox" id="is_weekend" name="is_weekend" value="1">

            <label for="specific_date">Available on specific date:</label>
            <input type="date" id="specific_date" name="specific_date">
            <br>
            <label for="selected_times">Select Time:</label><br>
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

            <button type="submit">Add to Schedule</button>
        </form>

        <h2>Available Dates</h2>

        <form method="post" action="">
            <label for="filter-day">Filter by Day:</label>
            <select id="filter-day" name="filter-day" class="filter-dropdown">
                <option value="all">All Days</option>
                <option value='Monday'>Monday</option>
                <option value='Tuesday'>Tuesday</option>
                <option value='Wednesday'>Wednesday</option>
                <option value='Thursday'>Thursday</option>
                <option value='Friday'>Friday</option>
                <option value='Saturday'>Saturday</option>
                <option value='Sunday'>Sunday</option>
            </select>

            <label for="filter-status">Filter by Status:</label>
            <select id="filter-status" name="filter-status" class="filter-dropdown">
                <option value="all">All Status</option>
                <option value='occupied'>Occupied</option>
                <option value='available'>Available</option>
            </select>
            <?php

            if (!empty($added_dates)) {
                echo "<table id='availability-table' border='1'>";
                echo "<thead>";
                echo "<tr><th>Date</th><th>Day</th><th>Start Time</th><th>End Time</th><th>Session Type</th><th>Current Status</th>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($added_dates as $date => $data) {
                    echo "<tr>";
                    echo "<td>{$date}</td>";
                    echo "<td>{$data['day']}</td>";
                    echo "<td>{$data['start_time']}</td>";
                    echo "<td>{$data['end_time']}</td>";
                    echo "<td>{$data['session_type']}</td>";
                    echo "<td>{$data['schedule_status']}</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p>No dates added to the schedule.</p>";
            }
            ?>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var filterDayDropdown = document.getElementById('filter-day');
            var filterStatusDropdown = document.getElementById('filter-status');
            var availabilityTable = document.getElementById('availability-table');

            filterDayDropdown.addEventListener('change', handleDropdownChange);
            filterStatusDropdown.addEventListener('change', handleDropdownChange);

            function handleDropdownChange() {
                var selectedDay = filterDayDropdown.value;
                var selectedStatus = filterStatusDropdown.value;

                var rows = availabilityTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    var dayCell = row.getElementsByTagName('td')[1];
                    var statusCell = row.getElementsByTagName('td')[5];
                    var showRow = (selectedDay === 'all' || dayCell.textContent === selectedDay) &&
                        (selectedStatus === 'all' || statusCell.textContent.toLowerCase() === selectedStatus);
                    row.style.display = showRow ? '' : 'none';
                }
            }
        });
    </script>
</body>

</html>

<style>
    body {
        background-color: #E0F4FF;
    }

    #availability-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #fff;
    }

    #availability-table th, #availability-table td {
        padding: 10px;
        text-align: center;
    }

    #availability-table th {
        background-color: #213555;
        color: #fff;
    }

    #availability-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #availability-table tr:hover {
        background-color: #ddd;
    }

    select {
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    button {
        padding: 10px;
        background-color: #4F709C;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
</style>
