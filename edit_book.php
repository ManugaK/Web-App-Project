<?php
include 'db_connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];
    $book_name = $_POST['book_name'];
    $category_id = $_POST['category_id'];

    $sql = "UPDATE book SET book_name=?, category_id=? WHERE book_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $book_name, $category_id, $book_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Book updated successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error updating book.";
        $_SESSION['message_type'] = "danger";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header("Location: view_books.php");
    exit();
}

if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    $sql = "SELECT * FROM book WHERE book_id='$book_id'";
    $result = mysqli_query($conn, $sql);
    $book = mysqli_fetch_assoc($result);
} else {
    header("Location: view_books.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Book</h1>
        <form action="edit_book.php" method="POST">
            <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
            <div class="mb-3">
                <label for="book_name" class="form-label">Book Name:</label>
                <input type="text" class="form-control" id="book_name" name="book_name" value="<?php echo $book['book_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category:</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <?php
                    $sql_categories = "SELECT * FROM bookcategory";
                    $result_categories = mysqli_query($conn, $sql_categories);
                    while($row_category = mysqli_fetch_assoc($result_categories)) {
                        $selected = ($book['category_id'] == $row_category['category_id']) ? "selected" : "";
                        echo "<option value='" . $row_category['category_id'] . "' $selected>" . $row_category['category_Name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
<!-- Button to go to register_book.html -->
<a href="register_book.html" class="btn btn-secondary fixed-bottom mx-auto d-block mb-3">Main Menu</a>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
