<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/Directories.php");
require_once(ROOT_DIR . "/includes/header.php");
require_once(ROOT_DIR . '/app/config/DatabaseConnect.php');
session_start();
require_once(ROOT_DIR."/views/components/page-guard.php"); 
// Initialize database connection
$db = new DatabaseConnect();
$conn = $db->connectDB();
$conn->set_charset("utf8mb4"); // Ensure UTF-8 encoding for handling special characters
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';

// Retrieve book data based on ID
$book_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

// Redirect if the book ID is invalid
if ($book_id <= 0) {
    echo '<script>alert("Invalid book ID!"); window.location.href="admin_home.php";</script>';
    exit;
}

// Fetch the book's current data
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$book = $result->fetch_assoc();

if (!$book) {
    echo '<script>alert("Book not found!"); window.location.href="admin_home.php";</script>';
    exit;
}

// Function to handle image upload
function uploadImage($file, $current_image_path) {
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/pages/uploads/';

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
        $file_name = uniqid() . '-' . basename($file['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            // Delete the old image if it exists and is different from the new one
            if ($current_image_path && realpath($_SERVER['DOCUMENT_ROOT'] . $current_image_path) !== realpath($target_file)) {
                $old_file_path = $_SERVER['DOCUMENT_ROOT'] . $current_image_path;
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }
            return '/pages/uploads/' . $file_name;
        } else {
            echo '<script>alert("Failed to upload image.");</script>';
        }
    }

    return $current_image_path;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $title = isset($_POST['title']) && $_POST['title'] !== ''
        ? mysqli_real_escape_string($conn, $_POST['title'])
        : $book['title'];

    $author = isset($_POST['author']) && $_POST['author'] !== ''
        ? mysqli_real_escape_string($conn, $_POST['author'])
        : $book['author'];

    $category = isset($_POST['category']) && $_POST['category'] !== ''
        ? mysqli_real_escape_string($conn, $_POST['category'])
        : $book['category'];

    $isbn = isset($_POST['isbn']) && $_POST['isbn'] !== ''
        ? mysqli_real_escape_string($conn, $_POST['isbn'])
        : $book['ISBN'];

    // Allow description to accept any text
    $description = isset($_POST['description']) 
        ? mysqli_real_escape_string($conn, $_POST['description']) 
        : $book['description'];

    $copies = isset($_POST['copies']) && is_numeric($_POST['copies'])
        ? (int)$_POST['copies']
        : $book['copies'];

    // Update book details (without image)
    $update_query = "UPDATE books SET 
        title = ?, 
        author = ?, 
        category = ?, 
        ISBN = ?, 
        description = ?, 
        copies = ? 
        WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssssi", $title, $author, $category, $isbn, $description, $copies, $book_id);

    if (!$stmt->execute()) {
        error_log("SQL Error: " . $stmt->error); // Log the error
        die('Error updating book details. Please check server logs.');
    }
    $stmt->close();

    // Handle the image upload separately
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_path = uploadImage($_FILES['image'], $book['image_path']);
        if ($image_path !== $book['image_path']) {
            $image_query = "UPDATE books SET image_path = ? WHERE id = ?";
            $stmt = $conn->prepare($image_query);
            $stmt->bind_param("si", $image_path, $book_id);

            if (!$stmt->execute()) {
                error_log("SQL Error: " . $stmt->error); // Log the error
                die('Error updating book image. Please check server logs.');
            }
            $stmt->close();
        }
    }

    // Redirect to the admin home page on success
    header("Location: ../../admin_home.php");
    exit;
}

$conn->close();
?>