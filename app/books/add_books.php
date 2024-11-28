<?php

session_start();

require_once(__DIR__ . "/../config/Directories.php");
include("../config/DatabaseConnect.php");

$db = new DatabaseConnect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize user input
    $bookTitle = htmlspecialchars($_POST["bookTitle"]);
    $description = htmlspecialchars($_POST["description"]);
    $author = htmlspecialchars($_POST["author"]);
    $category = htmlspecialchars($_POST["category"]);
    $ISBN = htmlspecialchars($_POST["ISBN"]);

    // Debugging: Check POST data
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Validate user input
    if (trim($bookTitle) == "" || empty($bookTitle)) { 
        $_SESSION["mali"] = "Book Title field is empty";
        header("Location: " . BASE_URL . "views/admin/add.php");
        exit;
    }
    if (trim($author) == "" || empty($author)) { 
        $_SESSION["mali"] = "Author field is empty";
        header("Location: " . BASE_URL . "views/admin/add.php");
        exit;
    }
    if (trim($category) == "" || empty($category)) { 
        $_SESSION["mali"] = "Category field is empty";
        header("Location: " . BASE_URL . "views/admin/add.php");
        exit;
    }
    if (trim($ISBN) == "" || empty($ISBN)) { 
        $_SESSION["mali"] = "ISBN field is empty";
        header("Location: " . BASE_URL . "views/admin/add.php");
        exit;
    }
    if (trim($description) == "" || empty($description)) { 
        $_SESSION["mali"] = "Description field is empty";
        header("Location: " . BASE_URL . "views/admin/add.php");
        exit;
    }

    // Check if a book image is uploaded
    if (!isset($_FILES['img_url']) || $_FILES['img_url']['error'] !== 0) {
        $_SESSION["error"] = "No image attached";
        header("Location: " . BASE_URL . "views/admin/add.php");
        exit;
    }

    try {
        // Insert record into the database
        $conn = $db->connectDB();

        // Debugging: Check connection
        if ($conn) {
            echo "Database connection successful";
        } else {
            echo "Database connection failed";
            exit;
        }

        // SQL query to insert book details into the books table
        $sql = "INSERT INTO books (bookTitle, description, author, category, ISBN, created_at, updated_at) 
                VALUES (:p_book_title, :p_book_description, :p_author, :p_category, :p_isbn, NOW(), NOW())";

        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);

        // Data array for binding values
        $data = [
            ':p_book_title' => $bookTitle,
            ':p_book_description' => $description,
            ':p_author' => $author,
            ':p_category' => $category,
            ':p_isbn' => $ISBN
        ];

        // Execute the statement with the data
        if (!$stmt->execute($data)) {
            $errorInfo = $stmt->errorInfo();
            echo "Error executing SQL: " . htmlspecialchars($errorInfo[2]); // Show error message
            exit;
        }

        $lastId = $conn->lastInsertId();

        // Process the uploaded image
        $error = processImage($lastId);
        if ($error) {
            $_SESSION["mali"] = $error;
            header("Location: " . BASE_URL . "views/admin/add.php");
            exit;
        }

        $_SESSION["tama"] = "Book added successfully";
        header("Location: " . BASE_URL . "views/admin/index.php");
        exit;

    } catch (PDOException $e) {
        echo "Connection Failed: " . htmlspecialchars($e->getMessage());
        exit;
    }
}

function processImage($id) {
    global $db;

    // Retrieve $_FILES
    $path = $_FILES['img_url']['tmp_name']; // Actual file on tmp path
    $fileName = $_FILES['img_url']['name']; // File name
    $fileType = mime_content_type($path);

    if ($fileType != 'image/jpeg' && $fileType != 'image/png') {
        return "File is not a jpg/png file";
    }

    $newFileName = md5(uniqid($fileName, true));
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    $hashedName = $newFileName . '.' . $fileExt;

    $destination = ROOT_DIR . 'public/uploads/books/' . $hashedName;
    
    if (!move_uploaded_file($path, $destination)) {
        return "Transferring of image returns an error";
    }

    // Set the correct image URL variable
    $imageUrl = 'public/uploads/books/' . $hashedName;

    // Update the database with the image URL
    try {
        $conn = $db->connectDB();
        
        if (!$conn) {
            return "Database connection failed during image processing.";
        }

        $sql = "UPDATE books SET img_url = :p_image_url WHERE id = :p_id;";
        
        // Prepare and execute the update statement
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':p_image_url', $imageUrl);
        $stmt->bindParam(':p_id', $id);

        if (!$stmt->execute()) {
            return "Error updating image URL in database.";
        }
        
    } catch (PDOException $e) {
        return "Error: " . htmlspecialchars($e->getMessage());
    }

    return null; // No error occurred
}