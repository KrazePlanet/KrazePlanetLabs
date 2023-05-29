<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sqli654548";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$city_id = $_GET['city_id'];
$item_id = $_GET['item_id'];

$sql = "SELECT * FROM users WHERE city_id = '$city_id' AND item_id = '$item_id'";
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
