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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
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
            <button type="submit" class="btn btn-primary mt-3" name="update">Edit</button>
            <a href="admin_panel.php" class="btn btn-secondary mt-3">Cancel</a>
        </form>
    </div>
</body>
</html>
<?php
mysqli_close($conn);
?>