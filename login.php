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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
        rgba(0,0,0,0.2)
);
}
</style>
</head>
<body style="background-image: url('login.png');
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
    
    
        <?php
        if (isset($_POST["login"])) {
            $username = $_POST["username"];
            $password = $_POST["password"];
            require_once "database.php";
            $sql = "SELECT * FROM user WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($user) {
                if (password_verify($password, $user["password"])) {
                    session_start();
                    $_SESSION["user"] = "yes";
                    header("Location: index.php");
                    die();
                }else{
                    echo "<div class='alert alert-danger'>Password does not match.</div>";
                }
            }else{
                echo "<div class='alert alert-danger'>Username does not match.</div>";
            }
        }
        ?>
        <div class="container">
        <form action = "login.php" method = "post">
            <div class = "form-group">
                <input type ="text" placeholder ="Enter username:" name ="username" class ="form-control">
            </div>

            <div class = "form-group">
                <input type ="password" placeholder ="Enter password:" name ="password" class ="form-control">
            </div>

            <div class = "form-btn">
                <input type ="submit" value ="Login" name ="login" class ="btn btn-primary">
            </div>
        </form>
        <br/>
        <div><p>Not registered yet <a href="registration.php">Register Here</a></p>
    </div>
    </div>
</body>
</html>