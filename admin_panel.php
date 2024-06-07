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
            background-image: url('admin.png'); /* Replace with your image URL */
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
  <body>
<div class="pos-f-t">
        <div class="collapse" id="navbarToggleExternalContent">
          <div class="bg-dark p-4">
            <h5 class="text-white h4">Library Management System</h5>
            <span class="text-muted">Books are gateways to endless knowledge, creativity, and discovery, enriching the minds of all who explore them.</span>
          </div>

          <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="logout.php" style="color:red"><b>Logout</b></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
              <ul class="navbar-nav">
                <li class="nav-item active">
                  <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                
              </ul>
            </div>
          </nav>

        </div>
        <nav class="navbar navbar-dark bg-dark">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </nav>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
 <br><br>
 <h1 style="text-align:center; color:white">Admin Panel</h1><br><br>
    <div class="container-fluid blur-border">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($user['user_id']) ?></td>
                        <td><?= htmlspecialchars($user['first_name']) ?></td>
                        <td><?= htmlspecialchars($user['last_name']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['password']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <a href="edit_user.php?id=<?= htmlspecialchars($user['user_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_user.php?id=<?= htmlspecialchars($user['user_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
mysqli_close($conn);
?>