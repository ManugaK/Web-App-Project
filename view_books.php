<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">View Books</h1>
        <form method="GET" action="" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <label for="category" class="form-label">Select Category:</label>
                    <select name="category" id="category" class="form-select">
                        <option value="">All Categories</option>
                        <?php
                        include 'db_connection.php';
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
                        <a href='edit_book.php?book_id=" . $row["book_id"] . "' class='btn btn-warning btn-sm'>Edit</a>
                        <a href='delete_book.php?book_id=" . $row["book_id"] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this book?\")'>Delete</a>
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

        <!-- Button to go to register_book.html -->
        <a href="register_book.html" class="btn btn-secondary fixed-bottom mx-auto d-block mb-3">Main Menu</a>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</body>
</html>
