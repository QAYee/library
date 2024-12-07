<?php
include('../includes/db.php'); // Include database connection
include('../includes/session_start.php'); // Include session start

if (isset($_GET['id']) && isset($_SESSION['username'])) {
    $book_id = $_GET['id']; // Get the book ID from the URL
    $username = $_SESSION['username']; // Get the logged-in user's username

    // Check if the book request exists and is approved
    $query = "SELECT * FROM book_requests WHERE book_id = '$book_id' AND username = '$username' AND status = 'approved' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Update the book's copies count (i.e., increment the available copies)
        $update_query = "UPDATE books SET copies = copies + 1 WHERE id = '$book_id'";
        if (mysqli_query($conn, $update_query)) {
            // Update the book request status to 'returned' and set the actual returned date/time
            $current_datetime = date('Y-m-d H:i:s'); // Current date and time
            $update_request_query = "UPDATE book_requests 
                                     SET status = 'returned', actual_return_date = '$current_datetime' 
                                     WHERE book_id = '$book_id' AND username = '$username'";
            if (mysqli_query($conn, $update_request_query)) {
                // Update the return date in the transactions table
                $update_transaction_query = "UPDATE transactions 
                                              SET return_date = '$current_datetime' 
                                              WHERE book_id = '$book_id' AND username = '$username' AND return_date IS NULL";
                if (mysqli_query($conn, $update_transaction_query)) {
                    // Successfully updated all relevant records
                    echo "<script>alert('Book returned successfully!'); window.location.href = 'book_status.php';</script>";
                } else {
                    echo "Error updating transaction return date: " . mysqli_error($conn);
                }
            } else {
                echo "Error updating book request status: " . mysqli_error($conn);
            }
        } else {
            echo "Error updating book copies: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('No approved borrow request found for this book.'); window.location.href = 'book_status.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'book_status.php';</script>";
}
?>
