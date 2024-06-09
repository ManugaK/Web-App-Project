<?php
$servername = "127.0.0.1";  // database server address
$username = "root";  //  database username default xammp
$password = "";  //  database password default xammp
$dbname = "library_system";  //your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>