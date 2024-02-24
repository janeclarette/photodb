<?php
session_start(); 
include("../include/config.php"); 
include("../photographer/header.php"); 


if (!isset($_SESSION['PhotographerID'])) {
    header("Location: ../photodb/admin/login.php");
    exit();
}

$photographer_id = $_SESSION['PhotographerID']; 
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

        <button type="submit">Add to Schedule</button>
    </form>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $availability_month = $_POST["availability_month"];
    $is_weekday = isset($_POST["is_weekday"]) ? 1 : 0;
    $is_weekend = isset($_POST["is_weekend"]) ? 1 : 0;
    $specific_date = isset($_POST["specific_date"]) ? $_POST["specific_date"] : null;
    $schedule_status_id = 1; 

    if (!empty($specific_date)) {
      
        $availability_date = date('Y-m-d', strtotime($specific_date));

        $sql = "INSERT INTO availability_schedule (photographerID, availability_date, is_weekday, is_weekend, schedule_status_id) 
                VALUES ('$photographer_id', '$availability_date', '0', '0', '$schedule_status_id')";

        if (!mysqli_query($conn, $sql)) {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);

        } else {
            echo '<script>';
            echo 'alert("Date was added successfully");';
            echo 'window.location.href = "schedule.php";';
            echo '</script>';
        }
    } else {

        $start_date = new DateTime($availability_month . '-01');
        $end_date = new DateTime($start_date->format('Y-m-t'));

        $current_date = clone $start_date;

        while ($current_date <= $end_date) {

            if (($is_weekday && $current_date->format('N') < 6) || ($is_weekend && $current_date->format('N') >= 6)) {
                $availability_date = $current_date->format('Y-m-d');

                $sql = "INSERT INTO availability_schedule (photographerID, availability_date, is_weekday, is_weekend, schedule_status_id) 
                        VALUES ('$photographer_id', '$availability_date', '$is_weekday', '$is_weekend', '$schedule_status_id')";

                if (!mysqli_query($conn, $sql)) {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);

                }
            }

            $current_date->modify('+1 day');
        }

        echo '<script>';
        echo 'alert("Dates were added successfully");';
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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_changes'])) {
    foreach ($_POST['schedule_status'] as $date => $status_id) {
        $update_sql = "UPDATE availability_schedule 
                       SET schedule_status_id = $status_id 
                       WHERE photographerID = '$photographer_id' 
                       AND availability_date = '$date'";
        mysqli_query($conn, $update_sql);
    }
    echo '<script>';
    echo 'alert("Status updated successfully");';
    echo 'window.location.href = "schedule.php";'; 
    echo '</script>';
    exit();
}

$sql_fetch_dates = "SELECT availability_date, is_weekday, is_weekend, s.status_name AS sched_status, a.schedule_status_id
                    FROM availability_schedule a
                    LEFT JOIN sched_status s ON a.schedule_status_id = s.schedule_status_id
                    WHERE photographerID = '$photographer_id'";
$result_dates = mysqli_query($conn, $sql_fetch_dates);

$added_dates = [];

if ($result_dates) {
    while ($row = mysqli_fetch_assoc($result_dates)) {
        $availability_date = $row['availability_date'];
        $day_of_week = date('l', strtotime($availability_date));
        $status_id = $row['schedule_status_id'];
        $schedule_status = $row['sched_status'];
        $added_dates[$availability_date] = [
            'day' => $day_of_week,
            'schedule_status_id' => $status_id,
            'schedule_status' => $schedule_status,
        ];
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Your Photographer Page</title>
</head>

<body>
    <div class="container">
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

            <button type="submit" name="confirm_changes" class="confirm-button">Confirm Changes</button>
            <?php

            if (!empty($added_dates)) {
                echo "<table id='availability-table' border='1'>";
                echo "<thead>";
                echo "<tr><th>Date</th><th>Day</th><th>Current Status</th><th>Action</th></tr>";
                echo "</thead>";
                echo "<tbody>";  
                foreach ($added_dates as $date => $data) {
                    echo "<tr>";
                    echo "<td>{$date}</td>";
                    echo "<td>{$data['day']}</td>";
                    echo "<td>{$data['schedule_status']}</td>";
                    echo "<td>";
                    
                    echo "<select id='status_{$date}' name='schedule_status[{$date}]' data-date='{$date}' onchange='handleChangeStatus(event)'>";
                    echo "<option value='1' " . (($data['schedule_status_id'] == 1) ? 'selected' : '') . ">Available</option>";
                    echo "<option value='2' " . (($data['schedule_status_id'] == 2) ? 'selected' : '') . ">Occupied</option>";
                    echo "</select>";
                    echo "</td>";
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
                    var statusCell = row.getElementsByTagName('td')[2];
                    var showRow = (selectedDay === 'all' || dayCell.textContent === selectedDay) &&
                        (selectedStatus === 'all' || statusCell.textContent.toLowerCase() === selectedStatus);
                    row.style.display = showRow ? '' : 'none';

            function handleDropdownChange() {
     
                var selectedDay = filterDayDropdown.value;
                var selectedStatus = filterStatusDropdown.value;

  
                var rows = availabilityTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    var dayCell = row.getElementsByTagName('td')[1];
                    var statusCell = row.getElementsByTagName('td')[2];
                    var showRow = (selectedDay === 'all' || dayCell.textContent === selectedDay) &&
                        (selectedStatus === 'all' || statusCell.textContent === selectedStatus);
                    row.style.display = showRow ? '' : 'none';
                }
            }

            function handleChangeStatus(event) {
                var selectedStatus = event.target.value;
                var date = event.target.dataset.date;
                console.log("Date: " + date + ", New Status: " + selectedStatus);
            }
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