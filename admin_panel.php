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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="style.css"> -->
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">     
    <style>.table thead th {
            background-color: #4942E4;
            color: white; }
            .container{
          max-width: 600px;
          margin: 0 auto;
          padding: 50px;
          box-shadow: rgba(100,100,111,0.2)0px 7px 29px 0px;
          }
          body {
            background-image: url('admin.png'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        
        .blur-border {
            position: relative;
            padding: 20px;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.8); /* White background with opacity */
            overflow: hidden; /* Ensure pseudo-element stays within container */
        }

        .blur-border::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border: 2px solid rgba(0, 0, 0, 0.2); /* Transparent border */
            border-radius: 5px;
            filter: blur(10px); /* Blur effect */
            z-index: -1; /* Position behind the content */
      }
    </style>
  </head>