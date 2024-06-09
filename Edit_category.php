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

    
    // Update the category
    $sql = "UPDATE bookcategory SET category_id=?, category_Name=?, date_modified=NOW() WHERE category_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $new_category_id, $category_name, $old_category_id);

    if (mysqli_stmt_execute($stmt)) {
        // $_SESSION['message'] = "Category updated successfully.";
        // $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error updating category.";
        $_SESSION['message_type'] = "danger";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header("Location: View_categories.php");
    exit();
}

if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $sql = "SELECT * FROM bookcategory WHERE category_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $category_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $category = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    if (!$category) {
        $_SESSION['message'] = "Category not found.";
        $_SESSION['message_type'] = "danger";
        header("Location: View_categories.php");
        exit();
    }
} else {
    header("Location: View_categories.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('library5.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        
        .blur-border {
            position: relative;
            padding: 20px;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.8); 
            overflow: hidden; 
        }

        .blur-border::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border: 2px solid rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            filter: blur(10px); 
            z-index: -1; 
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
    <h2>Edit Category</h2>
    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
            <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            ?>
        </div>
    <?php endif; ?>
    <form action="Edit_category.php" method="POST">
        <input type="hidden" name="old_category_id" value="<?php echo $category['category_id']; ?>">
        <div class="mb-3">
            <label for="category_id" class="form-label">Category ID:</label>
            <input type="text" class="form-control" id="category_id" name="new_category_id" value="<?php echo $category['category_id']; ?>" required pattern="C\d{3}">
        </div>
        <div class="mb-3">
            <label for="category_name" class="form-label">Category Name:</label>
            <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo $category['category_Name']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="View_categories.php" class="btn btn-secondary">Cancel</a>
    </form>
    <p>Last modified: <?php echo $category['date_modified']; ?></p>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>