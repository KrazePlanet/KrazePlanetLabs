<?php
$server   = "localhost";
$username = "root"; // change for hosting
$password = "";     // change for hosting
$database = "KrazePlanetLabs_DB"; // ONE database

$conn = mysqli_connect($server, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$city_id = $_GET['city_id'];
$item_id = $_GET['item_id'];

$sql = "SELECT * FROM lab5 WHERE city_id = '$city_id' AND item_id = '$item_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"]. " - Name: " . $row["name"]. " - City ID: " . $row["city_id"]. " - Item ID: " . $row["item_id"]. "<br>";
    }
} else {
    echo "0 results";
}

$conn->close();
?>
