<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/Directories.php");
require_once(ROOT_DIR . "/includes/header.php");
require_once(ROOT_DIR . '/app/config/DatabaseConnect.php');
session_start();

$db = new DatabaseConnect();
$conn = $db->connectDB();

if (isset($_GET['id'])) {
    $bookId = $_GET['id'];

    $deleteQuery = "DELETE FROM books WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $bookId);

    if ($stmt->execute()) {
        echo "Book deleted successfully.";
    } else {
        echo "Error deleting book: " . $conn->error;
    }

    // Redirect back to the admin panel after deletion
    header("Location: ../../admin_home.php");
    exit;
} else {
    die("Invalid book ID.");
}
?>
