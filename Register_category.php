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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Category</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('library2.jpg'); 
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