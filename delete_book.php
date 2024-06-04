<?php
include 'db_connection.php';
session_start();

if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    
    $sql = "DELETE FROM book WHERE book_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $book_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Book deleted successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting book.";
        $_SESSION['message_type'] = "danger";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header("Location: view_books.php");
    exit();
} else {
    header("Location: view_books.php");
    exit();
}
