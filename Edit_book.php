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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_book_id = $_POST['current_book_id'];
    $book_id = $_POST['book_id'];
    $book_name = $_POST['book_name'];
    $category_id = $_POST['category_id'];

    // Check if the new book ID already exists
    if (isBookIdExists($conn, $book_id, $current_book_id)) {
        $_SESSION['message'] = "Error: Book ID already exists. Please use a different Book ID.";
        $_SESSION['message_type'] = "danger";

        // Store form values in session to repopulate the form
        $_SESSION['form_values'] = $_POST;
    } else {
        $sql = "UPDATE book SET book_id=?, book_name=?, category_id=? WHERE book_id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $book_id, $book_name, $category_id, $current_book_id);

        if (mysqli_stmt_execute($stmt)) {
            // $_SESSION['message'] = "Book updated successfully.";
            // $_SESSION['message_type'] = "success";
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: View_books.php");
            exit();
        } else {
            $_SESSION['message'] = "Error updating book.";
            $_SESSION['message_type'] = "danger";
            mysqli_stmt_close($stmt);
        }
    }
    
    mysqli_close($conn);
    header("Location: Edit_book.php?book_id=$current_book_id");
    exit();
}
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    $sql = "SELECT * FROM book WHERE book_id='$book_id'";
    $result = mysqli_query($conn, $sql);
    $book = mysqli_fetch_assoc($result);
} else {
    header("Location: View_books.php");
    exit();
}

// Repopulate form values if they are stored in the session
$form_values = isset($_SESSION['form_values']) ? $_SESSION['form_values'] : $book;
unset($_SESSION['form_values']);
?>