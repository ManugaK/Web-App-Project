<?php
include 'Db_connection.php';
session_start();

// Function to check if a book ID already exists in the database
function isBookIdExists($conn, $book_id, $current_book_id) {
    $sql = "SELECT COUNT(*) AS count FROM book WHERE book_id = ? AND book_id != ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $book_id, $current_book_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $count > 0;
}
