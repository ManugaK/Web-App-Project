<?php
include 'Db_connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_category_id = $_POST['old_category_id'];
    $new_category_id = $_POST['new_category_id'];
    $category_name = $_POST['category_name'];

    // Check if the new category_id or category_name already exists
    $sql_check = "SELECT * FROM bookcategory WHERE (category_id = ? OR category_Name = ?) AND category_id != ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "sss", $new_category_id, $category_name, $old_category_id);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($result_check) > 0) {
        $_SESSION['message'] = "Category ID or Category Name already exists.";
        $_SESSION['message_type'] = "danger";
        mysqli_stmt_close($stmt_check);
        mysqli_close($conn);
        header("Location: Edit_category.php?category_id=$old_category_id");
        exit();
    }

    mysqli_stmt_close($stmt_check);