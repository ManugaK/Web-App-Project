<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

include 'Db_connection.php';

// Initialize message variables
$message = '';
$message_type = '';

// Function to check if a category ID already exists in the database
function isCategoryIdExists($conn, $category_id) {
    $sql = "SELECT COUNT(*) AS count FROM bookcategory WHERE category_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $category_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $count > 0;
}
function isCategorynameExists($conn, $category_name) {
    $sql = "SELECT COUNT(*) AS count FROM bookcategory WHERE category_name = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $category_name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $count > 0;
}
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];

    // Validate the Category ID format
    if (!preg_match("/^C\d{3}$/", $category_id)) {
        // Invalid Category ID format, display error
        $message = "Invalid Category ID format. Please use the format C<3-digit number> (e.g., C001).";
        $message_type = "danger";
    } else {
        // Check if the Category ID already exists
        if (isCategoryIdExists($conn, $category_id)) {
            $message = "Category ID already exists. Please choose a different ID.";
            $message_type = "danger";
        } 
        elseif (isCategorynameExists($conn, $category_name)) {
            $message = "Category Name already exists. Please choose a different Name.";
            $message_type = "danger";
        } 
        
        else {
            // Insert data into the database
            $sql = "INSERT INTO bookcategory (category_id, category_Name, date_modified) VALUES (?, ?, NOW())";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $category_id, $category_name);

            if (mysqli_stmt_execute($stmt)) {
                $message = "Category registered successfully";
                $message_type = "success";
            } else {
                $message = "Error: Unable to register the category.";
                $message_type = "danger";
            }

            mysqli_stmt_close($stmt);
        }
    }
}

mysqli_close($conn);
?>