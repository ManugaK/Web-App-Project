<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}
require 'database.php';
if (isset($_GET['id'])) {
    $user_id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM user WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    if (!$user) {
        echo "User not found.";
        exit;
    }
} else {
    header("Location: admin_panel.php");
    exit;
}
if (isset($_POST["update"])) {
    $userid = mysqli_real_escape_string($conn, $_POST["User_ID"]);
    $firstname = mysqli_real_escape_string($conn, $_POST["Firstname"]);
    $lastname = mysqli_real_escape_string($conn, $_POST["Lastname"]);
    $username = mysqli_real_escape_string($conn, $_POST["Username"]);
    $email = mysqli_real_escape_string($conn, $_POST["Email"]);
    $password = mysqli_real_escape_string($conn, $_POST["Password"]);
    $errors = array();
    if (empty($userid) OR empty($firstname) OR empty($lastname) OR empty($username) OR empty($email)){
        array_push($errors,"All fields except password are required.");
    }
    if (!preg_match('/^U[0-9]{3}$/', $userid)){
        array_push($errors,"Invalid user ID format. Use 'U<BOOK_ID>' format.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        array_push($errors,"Invalid email format."); 
    }
    if (!empty($password) && strlen($password) < 8){
        array_push($errors, "Password must be more than 8 characters.");
    }
    $sql = "SELECT * FROM user WHERE user_id = '$userid'";
    $result = mysqli_query($conn,$sql);
    $rowCount = mysqli_num_rows($result);
    if ($user_id!=$userid && $rowCount > 0){
        array_push($errors, "User ID already exists.");
    }
    // Check for existing username if it has changed
    if ($username !== $user['username']) {
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Username already exists.");
        }
    }
    if (count($errors) == 0){
        $sql = "UPDATE user SET user_id = ?, first_name = ?, last_name = ?, username = ?, email = ?";
        $params = [$userid, $firstname, $lastname, $username, $email];
        if (!empty($password)) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password = ?";
            $params[] = $passwordHash;
        }
        $sql .= " WHERE user_id = ?";
        $params[] = $user_id;
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, str_repeat('s', count($params)), ...$params);
            mysqli_stmt_execute($stmt);
            header("Location: admin_panel.php");
            exit;
        } else {
            echo "Something went wrong!";
        }
    }
}
?>