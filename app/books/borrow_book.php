<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
require_once(ROOT_DIR."/includes/header.php");
require_once(ROOT_DIR.'/app/config/DatabaseConnect.php');

session_start();
// Initialize the database connection
$db = new DatabaseConnect();
$conn = $db->connectDB();


        // Check if the user is logged in
        if (!isset($_SESSION['username'])) {
            header('Location: login.php');  // Redirect to login if not logged in
            exit;
        }

        $username = $_SESSION['username'];  // Logged-in user

        // Check if book ID is provided in the URL
        if (isset($_GET['id'])) {
            $book_id = $_GET['id'];
            
            // Sanitize the book ID to prevent SQL injection
            $book_id = mysqli_real_escape_string($conn, $book_id);

            // Check if the book exists in the database
            $query = "SELECT * FROM books WHERE id = '$book_id' LIMIT 1";
            $result = mysqli_query($conn, $query);
            
            if (!$result || mysqli_num_rows($result) == 0) {
                die("Invalid book ID.");
            }
            
            $book = mysqli_fetch_assoc($result);  // Get book details
            $bookTitle = $book['title'];

            // Check if the user already made a request for this book
            $checkRequest = "SELECT * FROM book_requests WHERE book_id = '$book_id' AND username = '$username' LIMIT 1";
            $requestResult = mysqli_query($conn, $checkRequest);
            
            if (mysqli_num_rows($requestResult) > 0) {
                // If request already exists, show a message and exit
                echo "You have already requested this book.";
                header("Location: " . BASE_URL . "user_home.php");
            } else {
                // Calculate estimated return date (5 days from the current date)
                $estimated_return_date = date('Y-m-d', strtotime('+5 days'));

                // Insert the request with the estimated return date
                $insertRequest = "INSERT INTO book_requests (book_id, username, status, estimated_return_date) 
                                  VALUES ('$book_id', '$username', 'pending', '$estimated_return_date')";

                if (mysqli_query($conn, $insertRequest)) {
                    echo "<script>alert('Your request for \"$bookTitle\" has been placed. Waiting for approval.'); window.location.href='" . BASE_URL . "user_home.php';</script>";
                } else {
                    echo "<script>alert('Error: Could not place the request.'); window.location.href='" . BASE_URL . "user_home.php';</script>";
                }
            }
        } else {
            echo "Book ID is missing.";
        }
?>
