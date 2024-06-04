<?php
session_start();
if (!isset($_SESSION['message'])) {
    header("Location: reg.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Registration Success</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container">
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> mt-4" role="alert">
            <i class="bi bi-check-circle" style="font-size: 1.5rem;"></i>
            <?php
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
        </div>
        <h2 class="my-4">Register Another Book</h2>
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
                    <option value="C001">Sci-fi</option>
