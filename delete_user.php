<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

require 'database.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "DELETE FROM user WHERE user_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $user_id);
        mysqli_stmt_execute($stmt);
    }
}

header("Location: admin_panel.php");
exit;
?>