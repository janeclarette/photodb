<?php
session_start();

include("../include/config.php");
include("../photographer/header.php");

if (!isset($_SESSION['PhotographerID'])) {
    header("Location: /photodb/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $placeID = $_GET['id'];

    // Delete the place
    $deletePlaceQuery = "DELETE FROM Places WHERE PlaceID = '$placeID'";
    if ($conn->query($deletePlaceQuery)) {
        echo '<script>';
        echo 'alert("Place deleted successfully");';
        echo 'window.location.href = "place.php";';
        echo '</script>';
    } else {
        echo "Error: " . $conn->error;
    }
}
?>