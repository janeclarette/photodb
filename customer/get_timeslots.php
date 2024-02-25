<?php
include("../include/config.php");

if (isset($_GET['selectedDate'])) {
    $selectedDate = $_GET['selectedDate'];

    // Fetch available time slots for the selected date
    $timeQuery = "SELECT DISTINCT t.start_time, t.end_time
    FROM time t
    JOIN availability_time at ON t.time_id = at.time_id
    JOIN availability_schedule a ON at.scheduleid = a.scheduleid
    JOIN available_date ad ON a.date_id = ad.date_id
    WHERE ad.avail_date = ? AND a.schedule_status_id = 1";

    $stmt = mysqli_prepare($conn, $timeQuery);
    mysqli_stmt_bind_param($stmt, "s", $selectedDate);
    mysqli_stmt_execute($stmt);
    $timeResult = mysqli_stmt_get_result($stmt);

    $timeSlots = [];

    while ($row = mysqli_fetch_assoc($timeResult)) {
        $startTime = date("h:i A", strtotime($row['start_time']));
        $endTime = date("h:i A", strtotime($row['end_time']));
        $timeSlots[] = "{$startTime} - {$endTime}";
    }

    echo json_encode($timeSlots);
} else {
    echo json_encode([]);
}

mysqli_close($conn);
?>
