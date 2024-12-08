<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/Directories.php");
require_once(ROOT_DIR . "/includes/header.php");
require_once(ROOT_DIR . '/app/config/DatabaseConnect.php');
session_start();

$db = new DatabaseConnect();
$conn = $db->connectDB();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';

// Validate book ID
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0) {
    echo '<script>alert("Invalid book ID!"); window.location.href="../../admin_home.php";</script>';
    exit;
}
$book_id = (int)$_GET['id'];

// Fetch book data
$stmt = mysqli_prepare($conn, "SELECT * FROM books WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $book_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$book = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$book) {
    echo '<script>alert("Book not found!"); window.location.href="../../admin_home.php";</script>';
    exit;
}

// Function to handle image uploads
function uploadImage($file, $current_image_path) {
    $upload_dir = $_SERVER["DOCUMENT_ROOT"] . "/uploads/";

    // Check if a file was uploaded
    if (isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Validate file extension
        if (!in_array($file_extension, $allowed_extensions)) {
            throw new Exception("Invalid file type. Allowed types are: " . implode(', ', $allowed_extensions));
        }

        // Generate unique file name
        $new_filename = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            throw new Exception("Failed to upload file.");
        }

        // Delete the old image if it exists
        if ($current_image_path && file_exists($_SERVER["DOCUMENT_ROOT"] . $current_image_path)) {
            unlink($_SERVER["DOCUMENT_ROOT"] . $current_image_path);
        }

        // Return the relative path of the uploaded file
        return "/uploads/" . $new_filename;
    }

    // Return the current image path if no new file was uploaded
    return $current_image_path;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $copies = isset($_POST['copies']) ? (int)$_POST['copies'] : 0;

    try {
        $image_path = uploadImage($_FILES['image'], $book['image_path']);
    } catch (Exception $e) {
        echo '<script>alert("' . $e->getMessage() . '");</script>';
        $image_path = $book['image_path']; // Retain the current image path on failure
    }

    $update_query = "UPDATE books SET title = ?, author = ?, category = ?, ISBN = ?, description = ?, image_path = ?, copies = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssssisii", $title, $author, $category, $isbn, $description, $image_path, $copies, $book_id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../../admin_home.php");
        exit;
    } else {
        error_log("MySQL error: " . mysqli_stmt_error($stmt));
        echo '<script>alert("Failed to update book.");</script>';
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>
