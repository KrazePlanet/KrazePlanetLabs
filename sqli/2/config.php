<?php
$server   = "localhost";
$username = "root"; // change for hosting
$password = "";     // change for hosting
$database = "KrazePlanetLabs_DB"; // ONE database

$conn = mysqli_connect($server, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
