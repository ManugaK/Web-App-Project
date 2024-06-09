<?php
include 'Db_connection.php';
session_start();

if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    
    $sql = "DELETE FROM bookcategory WHERE category_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $category_id);

    if (mysqli_stmt_execute($stmt)) {
        // $_SESSION['message'] = "Category deleted successfully.";
        // $_SESSION['message_type'] = "success";
    } else {
        // $_SESSION['message'] = "Error deleting category.";
        // $_SESSION['message_type'] = "danger";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header("Location: View_categories.php");
    exit();
} else {
    header("Location: View_categories.php");
    exit();
}
?>
