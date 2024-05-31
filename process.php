<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $categoryId = $_POST['categoryId'];
        $categoryName = $_POST['categoryName'];
        $dateModified = $_POST['dateModified'];

        $stmt = $conn->prepare("INSERT INTO bookcategory (category_id, category_Name, date_modified) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $categoryId, $categoryName, $dateModified);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'delete') {
        $categoryId = $_POST['categoryId'];

        $stmt = $conn->prepare("DELETE FROM bookcategory WHERE category_id = ?");
        $stmt->bind_param("s", $categoryId);
        $stmt->execute();
        $stmt->close();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET['action'] === 'load') {
        $result = $conn->query("SELECT * FROM bookcategory");
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($categories);
    }
}

$conn->close();
?>
