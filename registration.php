<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Register Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="style.css"> -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container{
    max-width: 600px;
    margin: 0 auto;
    padding: 50px;
    box-shadow: rgba(100,100,111,0.2)0px 7px 29px 0px;
    background-color: transparent;
    backdrop-filter: blur(20px);
    background-image: linear-gradient(
        120deg,
        rgba(255,255,255,0.3),
        rgba(0,0,0,0.2));
        }
</style>
</head>
<body style="background-image: url('registration.png');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center center;">
<div class="pos-f-t">
          <div class="bg-dark p-4">
            <h5 class="text-white h4">Library Management System</h5>
            <span class="text-muted">Books are gateways to endless knowledge, creativity, and discovery, enriching the minds of all who explore them.</span>
          </div>
</div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script><br><br>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
            $userid = $_POST["User_ID"];
            $firstname = $_POST["Firstname"];
            $lastname = $_POST["Lastname"];
            $username = $_POST["Username"];
            $password = $_POST["Password"];
            $email = $_POST["Email"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();

            require_once "database.php";

            $sql = "SELECT * FROM user WHERE user_id = '$userid'";
            $result = mysqli_query($conn,$sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0){
                array_push($errors, "User ID already exists.");
            }

            $sql = "SELECT * FROM user WHERE email = '$email'";
            $result = mysqli_query($conn,$sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0){
                array_push($errors, "Email already exists.");
            }
        
            $sql = "SELECT * FROM user WHERE username = '$username'";
            $result = mysqli_query($conn,$sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0){
                array_push($errors, "Username already exists.");
            }

            if(count($errors) > 0){
                foreach ($errors as $error){
                    echo "<div class = 'alert alert-danger'>$error</div>";
                }
            }else{
             
                $sql = "INSERT INTO user (user_id, email, first_name, last_name, username, password) VALUES ( ?, ?, ?, ?, ?, ? )";
                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
                if ($prepareStmt){
                    mysqli_stmt_bind_param($stmt, "ssssss", $userid, $email, $firstname, $lastname, $username, $passwordHash );
                    mysqli_stmt_execute($stmt);
                    echo "<div class = 'alert alert-success'>You are registered succesfully.</div>";
                    
                }else{
                    die("Something went wrong!");
                }
            }

        }
        ?>
    <form action="registration.php" method="post">
        <div class="form-group"> 
            <input type="text" class="form-control" name="User_ID" placeholder="e.g. U001" required pattern= "U\d{3}">
        </div>
        <div class="form-group"> 
            <input type="text" class="form-control" name="Firstname" placeholder="Firstname:" required>
        </div>
        <div class="form-group"> 
            <input type="text" class="form-control" name="Lastname" placeholder="Lastname:" required>
        </div>
        <div class="form-group"> 
            <input type="text" class="form-control" name="Username" placeholder="Username:" required>
        </div>
        <div class="form-group"> 
            <input type="password" class="form-control" name="Password" placeholder="Password:" required pattern= ".{8,}" >
        </div>
        <div class="form-group"> 
            <input type="email" class="form-control" name="Email" placeholder="Email:" required >
        </div>
        <div class="form-btn"> 
            <input type="submit" class="btn btn-primary" value="Register" name="submit">
        </div>
    </form>
    <br/>
    <div><p>Already Registered <a href="login.php">Login Here</a></p></div>
    </div>
</body>
</html>