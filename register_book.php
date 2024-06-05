<?php
session_start(); // Start the session
include 'db_connection.php';

// Function to check if a book ID already exists in the database
function isBookIdExists($conn, $book_id) {
    $sql = "SELECT COUNT(*) AS count FROM book WHERE book_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $book_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $count > 0;
}

// Initialize message variables
$message = '';
$message_type = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['bid'];
    $book_name = $_POST['bname'];
    $category = $_POST['category'];

    // Check if the category exists in the bookcategory table
    $category_check_query = "SELECT * FROM bookcategory WHERE category_id = '$category'";
    $result = mysqli_query($conn, $category_check_query);
    if (mysqli_num_rows($result) == 0) {
        // Category doesn't exist, display error
        $message = "Error: Category does not exist.";
        $message_type = "danger";
    } elseif (!preg_match("/^B\d{3}$/", $book_id)) {
        // Invalid Book ID format, display error
        $message = "Invalid Book ID format. Please use the format B<3-digit number> (e.g., B001).";
        $message_type = "danger";
    } elseif (isBookIdExists($conn, $book_id)) {
        // Book ID already exists, display error
        $message = "Error: Book ID already exists. Please use a different Book ID.";
        $message_type = "danger";
    } else {
        // Insert data into the database
        $sql = "INSERT INTO book (book_id, book_name, category_id) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $book_id, $book_name, $category);

        if (mysqli_stmt_execute($stmt)) {
            $message = "New book registered successfully";
            $message_type = "success";
        } else {
            $message = "Error: Unable to register the book.";
            $message_type = "danger";
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="my-4">Book Registration</h2>
        
        <?php if (!empty($message) && !empty($message_type)): ?>
        <div class="alert alert-<?php echo $message_type; ?> mt-3" role="alert">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <form id="bookForm" action="register_book.php" method="post">
            <div class="mb-3">
                <label for="bid" class="form-label">Book ID:</label>
                <input type="text" class="form-control" id="bid" name="bid" placeholder="Enter book ID (e.g., B001)" required pattern="B\d{3}">
            </div>
            <div class="mb-3">
                <label for="bname" class="form-label">Book Name:</label>
                <input type="text" class="form-control" id="bname" name="bname" placeholder="Enter book name" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Book Category:</label>
                <select class="form-select" id="category" name="category" required>
    <option value="">Select category</option>
    <?php
    include 'db_connection.php';
    
    // SQL query to select category names from database
    $sql = "SELECT category_id, category_Name FROM bookcategory";
    $result = mysqli_query($conn, $sql);

    // Check if there are any results
    if (mysqli_num_rows($result) > 0) {
        // Output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['category_id'] . "'>" . $row['category_Name'] . "</option>";
        }
    } else {
        echo "<option value=''>No categories found</option>";
    }
    ?>
</select>

            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
<!-- Button to go to register_book.html -->
<a href="register_book.html" class="btn btn-secondary fixed-bottom mx-auto d-block mb-3">Main Menu</a>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
