<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sqli65454";
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Get the username from the form
  $username = $_GET['username'];

  // Build the SQL query
  $sql = "SELECT * FROM users WHERE username = '{$username}' AND SLEEP(10)";

  // Execute the query
  $result = mysqli_query($conn, $sql);

  // Check if any rows were returned
  if (mysqli_num_rows($result) > 0) {
    // Display the user information
    while ($row = mysqli_fetch_assoc($result)) {
      echo "Username: " . $row['username'] . "<br>";
      echo "Password: " . $row['password'] . "<br>";
    }
  } else {
    echo "No users found";
  }

  // Close the database connection
  mysqli_close($conn);
}
?>
