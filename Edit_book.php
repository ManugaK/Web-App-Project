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


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('editbook.jpg'); 
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .blur-border {
            position: relative;
            padding: 20px;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.8); /* White background with opacity */
            overflow: hidden; /* Ensure pseudo-element stays within container */
        }

        .blur-border::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border: 2px solid rgba(0, 0, 0, 0.2); /* Transparent border */
            border-radius: 5px;
            filter: blur(10px); /* Blur effect */
            z-index: -1; /* Position behind the content */
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
                        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
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

<div class="container mt-5 blur-border">
    <h1>Edit Book</h1>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
            <?php echo $_SESSION['message']; unset($_SESSION['message'], $_SESSION['message_type']); ?>
        </div>
    <?php endif; ?>
    <form action="Edit_book.php" method="POST">
        <input type="hidden" name="current_book_id" value="<?php echo $book['book_id']; ?>">
        <div class="mb-3">
            <label for="book_id" class="form-label">Book ID:</label>
            <input type="text" class="form-control" id="book_id" name="book_id" value="<?php echo htmlspecialchars($form_values['book_id']); ?>" required pattern="B\d{3}">
        </div>
        <div class="mb-3">
            <label for="book_name" class="form-label">Book Name:</label>
            <input type="text" class="form-control" id="book_name" name="book_name" value="<?php echo htmlspecialchars($form_values['book_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Category:</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <?php
                $sql_categories = "SELECT * FROM bookcategory";
                $result_categories = mysqli_query($conn, $sql_categories);
                while($row_category = mysqli_fetch_assoc($result_categories)) {
                    $selected = ($form_values['category_id'] == $row_category['category_id']) ? "selected" : "";
                    echo "<option value='" . $row_category['category_id'] . "' $selected>" . $row_category['category_Name'] . "</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="View_books.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
