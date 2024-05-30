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
    <link rel="stylesheet" href="style.css">
</head>
<body>
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

            if (empty($userid) OR empty($firstname) OR empty($lastname) OR empty($username) OR empty($password) OR empty($email)){
                array_push($errors,"All fields are required.");
            }
            if (!preg_match('/^U[0-9]{3}$/', $userid)){
                array_push($errors,"Invalid user ID format. Use 'U&lt;BOOK_ID&gt;' format.");
            }
            if (strlen($password) < 8 ){
                array_push($errors, "Password must be more than 8 characters.");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                array_push($errors,"Invalid email format."); 
            }
            require_once "database.php";
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
            <input type="text" class="form-control" name="User_ID" placeholder="e.g. U001">
        </div>
        <div class="form-group"> 
            <input type="text" class="form-control" name="Firstname" placeholder="Firstname:">
        </div>
        <div class="form-group"> 
            <input type="text" class="form-control" name="Lastname" placeholder="Lastname:">
        </div>
        <div class="form-group"> 
            <input type="text" class="form-control" name="Username" placeholder="Username:">
        </div>
        <div class="form-group"> 
            <input type="password" class="form-control" name="Password" placeholder="Password:">
        </div>
        <div class="form-group"> 
            <input type="email" class="form-control" name="Email" placeholder="Email:">
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