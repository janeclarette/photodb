<?php 
$db_host = "localhost";
$db_username = "root";
$db_passwd = "";
$db_name = "photodb";
$conn = mysqli_connect($db_host, $db_username, $db_passwd,$db_name);

if(!$conn)
{
    echo "error";
}
?>