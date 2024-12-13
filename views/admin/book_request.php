<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
require_once(ROOT_DIR."/includes/header_admin.php");
require_once(ROOT_DIR."/includes/navbar_admin.php");
require_once(ROOT_DIR.'/app/config/DatabaseConnect.php');


// Initialize the database connection
$db = new DatabaseConnect();
$conn = $db->connectDB();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];  // Get the logged-in user's username
} else {
    $username = 'Admin';  // Default value if user is not logged in
}
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'User'; // User role (Admin, User)

// Check if the user is logged in (session 'username' should exist)
if (!isset($_SESSION['username'])) {
    // Redirect to the user home if not logged in
    header('Location: user_home.php');  // Adjust this URL to the correct user home page
    exit;
}

// Handle approve, decline, delete or edit action
if (isset($_GET['id']) && isset($_GET['action'])) {
    $request_id = $_GET['id'];  // Get the request ID
    $action = $_GET['action'];  // Get the action (approve/decline/delete/edit)
    
    // Sanitize input for security
    $request_id = mysqli_real_escape_string($conn, $request_id);
    $action = mysqli_real_escape_string($conn, $action);

    if ($action == 'approve') {
        // Update the status of the request in book_requests
        $status = 'approved';
        $query = "UPDATE book_requests SET status = '$status' WHERE request_id = '$request_id'";
        
        if (mysqli_query($conn, $query)) {
            // Fetch request details to insert into transactions table
            $fetch_query = "SELECT * FROM book_requests WHERE request_id = '$request_id'";
            $result = mysqli_query($conn, $fetch_query);
            $request_data = mysqli_fetch_assoc($result);

            // Get the username from the book_requests table
            $username = $request_data['username'];  // Assuming 'username' is in book_requests table

            // Fetch user_id (assuming the column is 'id' in users table)
            $user_query = "SELECT id FROM users WHERE username = '$username'";
            $user_result = mysqli_query($conn, $user_query);

            if ($user_result) {
                $user_data = mysqli_fetch_assoc($user_result);
                $user_id = $user_data['id'];  // Assign the user_id value
            } else {
                echo "<script>alert('Error fetching user_id: " . mysqli_error($conn) . "');</script>";
                exit;
            }

            // Get the request_date from the book_requests table
            $request_date = $request_data['request_date'];
            $book_id = $request_data['book_id'];

            // Insert into transactions table
            $transaction_query = "INSERT INTO transactions (book_id, username, user_id, borrow_date, status) 
                                VALUES ('$book_id', '$username', '$user_id', '$request_date', 'approved')";

            if (mysqli_query($conn, $transaction_query)) {
                // Hide the request from the book_requests table by updating 'is_hidden' to true
                $hide_query = "UPDATE book_requests SET is_hidden = 1 WHERE request_id = '$request_id'";

                if (mysqli_query($conn, $hide_query)) {
                    // Subtract one from the 'copies' field in the books table
                    $update_copies_query = "UPDATE books SET copies = copies - 1 WHERE id = '$book_id'";

                    if (mysqli_query($conn, $update_copies_query)) {
                        echo "<script>alert('Request has been approved, moved to transactions, hidden, and book copies updated.');</script>";
                    } else {
                        echo "<script>alert('Error updating book copies: " . mysqli_error($conn) . "');</script>";
                    }
                } else {
                    echo "<script>alert('Error hiding request: " . mysqli_error($conn) . "');</script>";
                }
            } else {
                echo "<script>alert('Error inserting transaction: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Error updating request: " . mysqli_error($conn) . "');</script>";
        }
    } elseif ($action == 'decline') {
        // Handle decline action
        $status = 'declined';
        $query = "DELETE FROM book_requests WHERE request_id = '$request_id'";

        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Request has been declined.');</script>";
        } else {
            echo "<script>alert('Error updating request: " . mysqli_error($conn) . "');</script>";
        }
    } elseif ($action == 'delete') {
        // Delete the request
        $query = "DELETE FROM book_requests WHERE request_id = '$request_id'";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Request has been deleted.');</script>";
        } else {
            echo "<script>alert('Error deleting request: " . mysqli_error($conn) . "');</script>";
        }
    } elseif ($action == 'edit') {
        // Update the status from the modal form
        if (isset($_POST['status'])) {
            $status = $_POST['status'];
            $query = "UPDATE book_requests SET status = '$status' WHERE request_id = '$request_id'";
            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Request status updated successfully.');</script>";
            } else {
                echo "<script>alert('Error updating request: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
}

// Fetch all book requests that are not approved or hidden
$query = "SELECT br.request_id, br.book_id, br.username, br.status, br.request_date, br.actual_return_date, b.title 
        FROM book_requests br 
        JOIN books b ON br.book_id = b.id 
        WHERE br.status != 'approved' AND br.is_hidden = 0";  // Exclude approved and hidden requests
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>
<?php require_once(ROOT_DIR."/views/components/page-guard.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        .request-container {
            background-color: #fff;
            padding: 15px;
            margin: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .request-container:hover {
            transform: scale(1.02);
        }

        .request-container p {
            margin: 5px 0;
        }

        .status {
            font-weight: bold;
            color: #333;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }

        .approve-btn {
            background-color: #28a745;
        }

        .approve-btn:hover {
            background-color: #218838;
        }

        .decline-btn {
            background-color: #dc3545;
        }

        .decline-btn:hover {
            background-color: #c82333;
        }

        .btn:focus {
            outline: none;
        }
    </style>
</head>
<body>
    
    <h1>Book Requests</h1>
    
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $request_id = $row['request_id'];
            $book_id = $row['book_id'];
            $book_title = $row['title'];
            $username = $row['username'];
            $status = $row['status'];
            $request_date = $row['request_date'];
            $actual_return_date = $row['actual_return_date'] ? $row['actual_return_date'] : "Not Returned";
            $return_date = date('Y-m-d', strtotime($request_date . ' +5 days'));

            echo "<div class='request-container'>
                    <p><strong>Book:</strong> $book_title</p>
                    <p><strong>User:</strong> $username</p>
                    <p><strong>Status:</strong> <span class='status'>" . ucfirst($status) . "</span></p>
                    <p><strong>Request Date:</strong> $request_date</p>
                    <p><strong>Expected Return Date:</strong> $return_date</p>
                    <p><strong>Actual Return Date:</strong> $actual_return_date</p>";

            if ($status == 'pending') {
                echo "<a href='book_request.php?id=$request_id&action=approve' class='btn approve-btn'>Approve</a>
                      <a href='book_request.php?id=$request_id&action=decline' class='btn decline-btn'>Decline</a>";
            }
            echo "</div>";
        }
    } else {
        echo "<p>No book requests found.</p>";
    }
    ?>

<?php require_once(ROOT_DIR."/includes/footer.php")?>
