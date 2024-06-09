<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

include 'Db_connection.php';

// Initialize message variables
$message = '';
$message_type = '';

// Function to check if a category ID already exists in the database
function isCategoryIdExists($conn, $category_id) {
    $sql = "SELECT COUNT(*) AS count FROM bookcategory WHERE category_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $category_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $count > 0;
}
function isCategorynameExists($conn, $category_name) {
    $sql = "SELECT COUNT(*) AS count FROM bookcategory WHERE category_name = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $category_name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $count > 0;
}