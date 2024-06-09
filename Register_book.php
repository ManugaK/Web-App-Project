<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}
session_start(); // Start the session
include 'Db_connection.php';

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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('registerbook.jpg'); 
            background-size: cover;
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
        <h2 class="my-4">Book Registration</h2>
        
        <?php if (!empty($message) && !empty($message_type)): ?>
        <div class="alert alert-<?php echo $message_type; ?> mt-3" role="alert">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <form id="bookForm" action="Register_book.php" method="post">
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
    include 'Db_connection.php';
    
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
    


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
