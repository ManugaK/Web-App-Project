<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('viewbooks.jpg'); 
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
        <h1 class="mb-4">View Books</h1>
        <form method="GET" action="" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <label for="category" class="form-label">Select Category:</label>
                    <select name="category" id="category" class="form-select">
                        <option value="">All Categories</option>
                        <?php
                        include 'Db_connection.php';
                        $sql_categories = "SELECT * FROM bookcategory";
                        $result_categories = $conn->query($sql_categories);
                        while ($row_category = $result_categories->fetch_assoc()) {
                            $selected = (isset($_GET['category']) && $_GET['category'] == $row_category['category_id']) ? "selected" : "";
                            echo "<option value='" . $row_category['category_id'] . "' $selected>" . $row_category['category_Name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <?php
        $sql = "SELECT b.book_id, b.book_name, bc.category_Name 
                FROM book b 
                JOIN bookcategory bc ON b.category_id = bc.category_id";
        
        if (isset($_GET['category']) && !empty($_GET['category'])) {
            $selected_category = $_GET['category'];
            $sql .= " WHERE b.category_id = '$selected_category'";
        }
        
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "<h2>Books</h2>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-striped'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Book ID</th>";
            echo "<th>Book Name</th>";
            echo "<th>Category</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["book_id"] . "</td>";
                echo "<td>" . $row["book_name"] . "</td>";
                echo "<td>" . $row["category_Name"] . "</td>";
                echo "<td>
                        <a href='Edit_book.php?book_id=" . $row["book_id"] . "' class='btn btn-warning btn-sm'>Edit</a>
                        <a href='Delete_book.php?book_id=" . $row["book_id"] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this book?\")'>Delete</a>
                      </td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-info'>No books found for the selected category.</div>";
        }
        ?>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</body>
</html>
