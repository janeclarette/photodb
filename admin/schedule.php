<?php
include("../include/config.php"); 
include("../admin/adminheader.php"); 

// Fetch transactions from the database
$sql = "SELECT * FROM transactions";
$result = $conn->query($sql);

// Initialize an array to store events for the calendar
$events = [];

if ($result->num_rows > 0) {
    // Loop through each transaction to generate events
    while ($row = $result->fetch_assoc()) {
        // Construct event details
        $event_title = "Booking: #" . $row['TransactionID'];
        $event_date = $row['ReservationDate'];
        $event_color = ''; // You can set different colors based on transaction status if needed

        // Add event to the events array
        $events[] = [$event_title, $event_date, 1, $event_color];
    }
} else {
    echo "No transactions found.";
}
$conn->close();

// Include the Calendar class
include("calendar.php");

// Initialize the calendar with the current date
$calendar = new Calendar();

// Add events to the calendar
foreach ($events as $event) {
    $calendar->add_event($event[0], $event[1], $event[2], $event[3]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Oversight</title>
    <!-- Include FullCalendar CSS -->
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.0/main.min.css' rel='stylesheet' />
    <!-- Include custom CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="cover-section"><h2>Booking Oversight</h2></div>

    <!-- Calendar Container -->
    <div id='calendar'>
        <?php echo $calendar; ?>
    </div>

    <!-- Include FullCalendar JavaScript -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.0/main.min.js'></script>
    <!-- Include custom JavaScript -->
    <script src='script.js'></script>
   
</body>
</html>
