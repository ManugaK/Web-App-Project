<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

require 'database.php';

$sql = "SELECT * FROM user";
$result = mysqli_query($conn, $sql);
?>
