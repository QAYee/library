<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
require_once(ROOT_DIR."/includes/header.php");
require_once(ROOT_DIR.'/app/config/DatabaseConnect.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Get the book ID from GET or POST
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 
           (isset($_POST['book_id']) ? intval($_POST['book_id']) : null);

if (!$book_id) {
    die("No valid Book ID provided");
}

$db = new DatabaseConnect();
$conn = $db->connectDB();

// Verify database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$conn->set_charset("utf8mb4");
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';

// Function to handle image upload
function uploadImage($file, $current_image_path, $book_id = null) {
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
            // Only log if book_id is provided
            if ($book_id !== null) {
                error_log("Failed to upload image for book ID: " . $book_id);
            }
            return $current_image_path;
        }
    }

    return $current_image_path;
}

// Retrieve book details
$select_query = "SELECT * FROM books WHERE id = ?";
$select_stmt = $conn->prepare($select_query);
$select_stmt->bind_param("i", $book_id);
$select_stmt->execute();
$result = $select_stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    die("Book not found with ID: " . $book_id);
}
$select_stmt->close();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $title = !empty($_POST['title']) 
        ? mysqli_real_escape_string($conn, $_POST['title']) 
        : $book['title'];

    $author = !empty($_POST['author']) 
        ? mysqli_real_escape_string($conn, $_POST['author']) 
        : $book['author'];

    $category = !empty($_POST['category']) 
        ? mysqli_real_escape_string($conn, $_POST['category']) 
        : $book['category'];

    $isbn = !empty($_POST['isbn']) 
        ? mysqli_real_escape_string($conn, $_POST['isbn']) 
        : $book['ISBN'];

    $description = !empty($_POST['description']) 
        ? mysqli_real_escape_string($conn, $_POST['description']) 
        : $book['description'];

    $copies = !empty($_POST['copies']) && is_numeric($_POST['copies'])
        ? (int)$_POST['copies']
        : $book['copies'];

    // Prepare update query for book details
    $update_query = "UPDATE books SET 
        title = ?, 
        author = ?, 
        category = ?, 
        ISBN = ?, 
        description = ?, 
        copies = ? 
        WHERE id = ?";
    
    $stmt = $conn->prepare($update_query);
    
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssssi", $title, $author, $category, $isbn, $description, $copies, $book_id);

    if (!$stmt->execute()) {
        error_log("Update Error: " . $stmt->error);
        die('Error updating book details: ' . $stmt->error);
    }
    $stmt->close();

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_path = uploadImage($_FILES['image'], $book['image_path'], $book_id);
        
        if ($image_path !== $book['image_path']) {
            $image_query = "UPDATE books SET image_path = ? WHERE id = ?";
            $img_stmt = $conn->prepare($image_query);
            
            if ($img_stmt === false) {
                die("Image prepare failed: " . $conn->error);
            }
            
            $img_stmt->bind_param("si", $image_path, $book_id);

            if (!$img_stmt->execute()) {
                error_log("Image Update Error: " . $img_stmt->error);
                die('Error updating book image: ' . $img_stmt->error);
            }
            $img_stmt->close();
        }
    }

    // Redirect on success
    header("Location: admin_home.php");
    exit;
}
?>