<?php
// Check if the 'date' parameter is set in the URL
if(isset($_GET['date'])) {
    // Get the current date from the URL parameter
    $current_date = $_GET['date'];
    
    // Convert the date to a PHP DateTime object
    $date_obj = new DateTime($current_date);

    // Check if the URL parameter 'action' is set and equals 'prev' or 'next'
    if(isset($_GET['action']) && ($_GET['action'] === 'prev' || $_GET['action'] === 'next')) {
        // Modify the date object based on whether the action is 'prev' or 'next'
        if($_GET['action'] === 'prev') {
            $date_obj->modify('-1 year');
        } elseif($_GET['action'] === 'next') {
            $date_obj->modify('+1 year');
        }
    }

    // Format the modified date as 'Y-m' (Year-Month)
    $new_date = $date_obj->format('Y-m');

    // Redirect the user back to the page with the updated date
    header("Location: your_calendar_page.php?date=$new_date");
    exit();
} else {
    // If the 'date' parameter is not set, redirect the user to a default page
    header("Location: default_page.php");
    exit();
}
?>
