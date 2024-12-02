<?php

if(!isset($_SESSION)){
    session_start();
}

require_once(__DIR__."/../config/Directories.php"); //to handle folder specific path
include("../config/DatabaseConnect.php"); //to access database connection

$db = new DatabaseConnect(); //make a new database instance

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $bookId = htmlspecialchars($_POST["id"]);
    $img_url2 = htmlspecialchars($_POST["img_url2"]);
    $bookTitle = htmlspecialchars($_POST["bookTitle"]);
    $description = htmlspecialchars($_POST["description"]);
    $author = htmlspecialchars($_POST["author"]);
    $category = htmlspecialchars($_POST["category"]);
    $ISBN = htmlspecialchars($_POST["ISBN"]);

    
     //validate user input
    
    
     if (trim($bookTitle) == "" || empty($bookTitle)) { 
        $_SESSION["error"] = "Book Title field is empty";
        header("location: ".BASE_URL."views/admin/books/edit.php");
        exit;
    }
    
    if (trim($author) == "" || empty($author)) { 
        $_SESSION["error"] = "Author field is empty";
        header("location: ".BASE_URL."views/admin/books/edit.php");
        exit;
    }
    
    if (trim($category) == "" || empty($category)) { 
        $_SESSION["error"] = "Category field is empty";
        header("location: ".BASE_URL."views/admin/books/edit.php");
        exit;
    }
    
    if (trim($ISBN) == "" || empty($ISBN)) { 
        $_SESSION["error"] = "ISBN field is empty";
        header("location: ".BASE_URL."views/admin/books/edit.php");
        exit;
    }
    
    if (trim($description) == "" || empty($description)) { 
        $_SESSION["error"] = "Description field is empty";
        header("location: ".BASE_URL."views/admin/books/edit.php");
        exit;
    }
    
    if (!isset($img_url2) || empty($img_url2)) {
        $_SESSION["error"] = "No image attached";
        header("location: ".BASE_URL."views/admin/books/edit.php");
        exit;
    }
    
 

    try {
        // Connect to the database
        $conn = $db->connectDB();
    
        // Update the book details in the database
        $sql = "UPDATE books 
                SET books.bookTitle = :p_book_title,
                    books.description = :p_description,
                    books.author = :p_author,
                    books.category = :p_category,
                    books.isbn = :p_isbn,
                    books.updated_at = NOW()
                WHERE books.id = :p_id";
    
        $stmt = $conn->prepare($sql);
        $data = [
            ':p_book_title'   => $bookTitle,
            ':p_description'  => $description,
            ':p_author'       => $author,
            ':p_category'  => $category,
            ':p_isbn'         => $ISBN,
            ':p_id'           => $bookId
        ];
    
        if (!$stmt->execute($data)) {
            $_SESSION["error"] = "Failed to update the record";
            header("location: " . BASE_URL . "views/admin/edit.php");
            exit;
        }
    
        $lastId = $bookId;
    
        // Handle image upload if provided
        if (isset($_FILES['img_url']) && $_FILES['img_url']['error'] == 0) {
            $error = processImage($lastId);
    
            if ($error) {
                $_SESSION["error"] = $error;
                header("Location: " . BASE_URL . "views/admin/edit.php");
                exit;
            }
        }
    
        $_SESSION["success"] = "Book updated successfully";
        header("location: " . BASE_URL . "views/admin/index.php");
        exit;
    
    } catch (PDOException $e) {
        echo "Connection Failed: " . $e->getMessage();
        $db = null;
    }
}

    function processImage($id){
        global $db;
        //retrieve $_FILES
        $path         = $_FILES['img_url']['tmp_name']; //actual file on tmp path
        $fileName     = $_FILES['img_url']['name']; //file name
        $fileType     =mime_content_type($path);
    
    
        if($fileType != 'image/jpeg' && $fileType  != 'image/png'){
            return "File is not jpg/png file";
        }
        
        
        $newFileName = md5(uniqid($fileName, true));
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        $hashedName = $newFileName.'.'.$fileExt;
    
        $destination = ROOT_DIR.'public/uploads/books/'.$hashedName;
        if(!move_uploaded_file($path,$destination)){
            return "transferring of image returns an error";
    
        }
    
        $imageUrl ='public/uploads/books/'.$hashedName;
    
        $conn = $db->connectDB();
        $sql = "UPDATE books  SET img_url = :p_image_url WHERE id = :p_id; ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':p_image_url',$imageUrl);
        $stmt->bindParam(':p_id',$id);
    
        $stmt->execute();
        if(!$stmt ->execute()){
            return "Failed to upload the image url field";
        };
        return null;
    }
