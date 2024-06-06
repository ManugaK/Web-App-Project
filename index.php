<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>User Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>.table thead th {
            background-color: #4942E4;
            color: white; }
            .navbar-custom {
        background-color: #11009E;
    }
    </style>
</head>
<body>
<div class="pos-f-t">
        <div class="collapse" id="navbarToggleExternalContent">
          
          <div class="navbar-custom p-4">
            <h5 class="text-white h4">Library Management System</h5>
            <span class="text-muted">Books are gateways to endless knowledge, creativity, and discovery, enriching the minds of all who explore them.</span>
          </div>

          <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="logout.php" style="color:red"><b>Logout</b></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          </nav>

        </div>
        <nav class="navbar navbar-dark navbar-custom">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <span class="navbar-text mx-auto text-white"><h4>Wellcome to Dashboard</h4></span>    
        </nav>
      </div>
  
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <br><br>
    
    
        <div style="text-align: center" class="container">
        <a href="admin_panel.php" class="btn btn-primary btn-lg mb-3">Admin Panel</a><br><br>
        <a href="admin_panel.php" class="btn btn-primary btn-lg mb-3">Add Book</a><br><br>
        <a href="index.html" class="btn btn-primary btn-lg mb-3">Add Book Category</a>
    </div>
</body>
</html>