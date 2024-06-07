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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="style.css"> -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
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
            background-image: url('edit_user.png'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        
        .blur-border {
            position: relative;
            padding: 20px;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.8); 
            overflow: hidden; 
        }
        .blur-border::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border: 2px solid rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            filter: blur(10px); 
            z-index: -1; 
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
                  <a class="nav-link" href="admin_panel.php">Admin panel <span class="sr-only">(current)</span></a>
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
<div class="container mt-5 blur-border">
        <h2 style="text-align:center">Edit User</h2><br>
        <?php if (isset($errors) && count($errors) > 0): ?>
            <?php foreach ($errors as $error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        <form action="edit_user.php?id=<?= htmlspecialchars($user['user_id']) ?>" method="post">
            <div class="form-group">
                <label for="User_ID">User ID</label>
                <input type="text" class="form-control" name="User_ID" value="<?= htmlspecialchars($user['user_id']) ?>" required>
            </div>
            <div class="form-group">
                <label for="Firstname">First Name</label>
                <input type="text" class="form-control" name="Firstname" value="<?= htmlspecialchars($user['first_name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="Lastname">Last Name</label>
                <input type="text" class="form-control" name="Lastname" value="<?= htmlspecialchars($user['last_name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="Username">Username</label>
                <input type="text" class="form-control" name="Username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>
            <div class="form-group">
                <label for="Email">Email</label>
                <input type="email" class="form-control" name="Email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="Password">Password (leave blank to keep unchanged)</label>
                <input type="password" class="form-control" name="Password" placeholder="Enter new password (optional)">
            </div>
            <button type="submit" class="btn btn-warning mt-3" name="update">Confirm</button>
            <a href="admin_panel.php" class="btn btn-secondary mt-3">Cancel</a>
        </form>
    </div>
</body>
</html>
<?php
mysqli_close($conn);
?>