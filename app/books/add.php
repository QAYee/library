<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
require_once(ROOT_DIR."/includes/header.php");
require_once(ROOT_DIR.'/app/config/DatabaseConnect.php');
session_start();

$db = new DatabaseConnect();
$conn = $db->connectDB();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $copies = isset($_POST['copies']) ? (int)$_POST['copies'] : 0;

    // Default image path if no image is uploaded
    $image_path = ROOT_DIR .'/pages/uploads/'; 

    // Check if a new image is uploaded
    // Check if a new image is uploaded
if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/pages/uploads/';
    $file_name = uniqid() . '-' . basename($_FILES['image_path']['name']);
    $target_file = $upload_dir . $file_name;

    // Ensure the upload directory exists
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true); // Create the directory if it doesn't exist
    }

    // Move the uploaded file to the upload directory
    if (move_uploaded_file($_FILES['image_path']['tmp_name'], $target_file)) {
        $image_path = '/pages/uploads/' . $file_name; // Save the relative path for storing in the database
    } else {
        echo 'Error moving the uploaded file.';
    }
}


    // Insert into the database
    $query = "INSERT INTO books (title, author, category, ISBN, description, image_path, copies) 
              VALUES ('$title', '$author', '$category', '$isbn', '$description', '$image_path', $copies)";

    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Book added successfully!"); window.location.href="../../admin_home.php";</script>';
    } else {
        echo '<script>alert("Failed to add book: ' . mysqli_error($conn) . '");</script>';
    }
}
?>
