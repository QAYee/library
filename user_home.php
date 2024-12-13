<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
require_once(ROOT_DIR."/includes/header.php");
require_once(ROOT_DIR.'/app/config/DatabaseConnect.php');

session_start();
// Initialize the database connection
$db = new DatabaseConnect();
$conn = $db->connectDB();

        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];  // Get the logged-in user's username
        } else {
            $username = 'Guest';  // Default value if user is not logged in
        }

        $query = "SELECT id, title, description, author, category, ISBN, copies, image_path FROM books";
        $result = mysqli_query($conn, $query);

        // Check if the query ran successfully
        if (!$result) {
            die("Database query failed: " . mysqli_error($conn));

            $query = "INSERT INTO book_requests (book_id, username, status) VALUES ('$book_id', '$username', 'pending')";
            if (mysqli_query($conn, $query)) {
                echo "Request submitted successfully!";
                header("Location: book_request.php"); // Redirect to the book requests page
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } 

?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Library System</title>
            <link rel="stylesheet" href="styles.css">
            <style>
                /* Navbar Styling */

                
                .navbar {
            display: flex;
            justify-content: flex-end; /* Align all elements to the right */
            align-items: center;
            background-color: #eee;
            color: black;
            padding: 1px;
        }

        .navbar a {
            color: #333; /* Black text for links */
            text-decoration: none;
            padding: 10px;
            margin-left: 10px;
        }

        .navbar a:hover {
            color: red; /* Change text color to red on hover */
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropbtn {
            background-color: transparent; /* No background color */
            color: #333; /* Black text */
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            border: none; /* Remove any border */
            outline: none; /* Remove outline on focus */
        }

        .dropbtn:hover {
            color: red; /* Change text color to red on hover */
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 150px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
            overflow: hidden;
        }

        .dropdown-content a {
            color: #333;
            padding: 10px;
            text-decoration: none;
            display: block;
            font-weight: normal;
        }

        .dropdown-content a:hover {
            background-color: #f2f2f2;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }


        /* Overall Container */
        .book-cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Reduced gap between cards */
            justify-content: center; /* Align cards to the left, without extra space on the right */
            padding: 20px;
        }

        /* Individual Book Card */
        .book-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 6px 18px rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: 270px; /* Smaller width for all cards */
            height: 500px; /* Adjusted height for better fit */
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Book Image */
        .book-card img {
            width: 100%;
            height: 220px; /* Adjusted height for portrait images */
            object-fit: contain; /* Ensures the full image is visible without cropping */
            border-radius: 8px;
            margin-bottom: 10px;
        }

        /* Book Title */
        .book-card h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        /* Book Description */
        .book-card p {
            font-size: 12px;
            color: #555;
            margin-bottom: 2px;
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Limit to 3 lines */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .book-title {
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            margin: 15px 0;
        }


        /* Button Container */
        .btn-container {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 10px;
        }

        /* Buttons Styling */
        .btn {
            padding: 6px 15px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .btn-borrow {
            background-color: #1976D2;
            color: white;
        }

        .btn-return {
            background-color: #d9534f;
            color: white;
        }

        .book-cards-container {
            margin-right: 0;
            justify-content: center;
        }
                /* Header Adjustment */
                .header {
                    text-align: center;
                    background-color: #a33b3b;
                    color: white;
                    padding: 1px 0; 
                }
            </style>
        </head>

        <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #e2e2e2;">

            <!-- Header -->
            <header class="header">
                <h1>Library System</h1>
            </header>

            <!-- Navbar -->
            <nav class="navbar">
                <a href="user_home.php">Home</a>
                <a href="<?php echo BASE_URL; ?>views/user/book_status.php">Borrowed Books</a> <!-- Book Request Link -->
                <div class="dropdown">
                    <button class="dropbtn">
                        <?php echo htmlspecialchars($username); ?> ▼
                    </button>
                    <div class="dropdown-content">
                        <a href="<?php echo BASE_URL; ?>logout.php" style="color: red;">Logout</a>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container" style="padding: 20px;">
                <!-- Search Bar -->
                <div style="margin-bottom: 20px;">
                    <form action="<?php echo BASE_URL;?>app/books/search_books.php" method="GET">
                        <input type="text" name="query" placeholder="Search Books" required style="width: calc(100% - 110px); padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px;">
                        <button type="submit" style="padding: 10px 20px; background-color: #a33b3b; color: white; border: none; border-radius: 5px; cursor: pointer;">Search</button>
                    </form>
                </div>

                
            
           
                


            <div class="book-cards-container">
            <?php
if (mysqli_num_rows($result) > 0) {
    while ($book = mysqli_fetch_assoc($result)) {
        $bookTitle = isset($book['title']) ? htmlspecialchars($book['title']) : 'No Title';
        $bookDescription = isset($book['description']) ? htmlspecialchars($book['description']) : 'No Description';
        $bookAuthor = isset($book['author']) ? htmlspecialchars($book['author']) : 'No Author';
        $bookCategory = isset($book['category']) ? htmlspecialchars($book['category']) : 'No Category';
        $bookIsbn = isset($book['ISBN']) ? htmlspecialchars($book['ISBN']) : 'No ISBN';
        $bookCopies = isset($book['copies']) ? htmlspecialchars($book['copies']) : '0';
        $bookTotalBorrows = isset($book['total_borrows']) ? htmlspecialchars($book['total_borrows']) : '0';
        $bookImage = isset($book['image_path']) ? htmlspecialchars($book['image_path']) : 'default-image.jpg';
        $username = $_SESSION['username'];
        $book_id = $book['id'];
        
        // Query to check the book request status for the current user
        $query = "SELECT * FROM book_requests WHERE book_id = '$book_id' AND username = '$username' LIMIT 1";
        $request_result = mysqli_query($conn, $query);
        $request = mysqli_fetch_assoc($request_result);

        if ($request) {
            $status = $request['status'];
            if ($status == 'pending') {
                $button_text = 'Pending';
                $button_class = 'btn-pending';
                $disabled = 'disabled'; // Button is disabled for pending requests
            } elseif ($status == 'approved') {
                $button_text = 'Borrowed';
                $button_class = 'btn-borrowed';
                $disabled = 'disabled'; // Button is disabled for approved requests
            } elseif ($status == 'returned') {
                $button_text = 'Borrow';
                $button_class = 'btn-borrow';
                $disabled = ''; // Enable the button for returned books, allowing borrowing again
                
                // Delete transaction for returned book
                $delete_query = "DELETE FROM book_requests WHERE book_id = '$book_id' AND username = '$username'";
                if (mysqli_query($conn, $delete_query)) {
                    
                } else {
                    echo "<p>Error deleting transaction: " . mysqli_error($conn) . "</p>";
                }
            } else { // For declined or other statuses
                $button_text = 'Declined';
                $button_class = 'btn-declined';
                $disabled = 'disabled'; // Button is disabled for declined requests
            }
        } else {
            $button_text = 'Borrow';
            $button_class = 'btn-borrow';
            $disabled = ''; // Enable the button if no request has been made
        }
?>
        <!-- Book Card -->
<div class="book-card">
  
    <a href="/app/books/history.php?id=<?php echo $book['id']; ?>">
        <img src="<?php echo $bookImage; ?>" alt="Book Image">
    </a>
    <h3 class="book-title"><?php echo $bookTitle; ?></h3>
    <p><strong>Description:</strong> <?php echo $bookDescription; ?></p>
    <p><strong>Author:</strong> <?php echo $bookAuthor; ?></p>
    <p><strong>Category:</strong> <?php echo $bookCategory; ?></p>
    <p><strong>ISBN:</strong> <?php echo $bookIsbn; ?></p>
    <p><strong>Copies Available:</strong> <?php echo $bookCopies; ?></p>
    <div class="btn-container">
        <a href="/app/books/borrow_book.php?id=<?php echo $book['id']; ?>" class="btn <?php echo $button_class; ?>" <?php echo $disabled; ?>><?php echo $button_text; ?></a>
        <?php if (isset($request) && $request['status'] == 'approved') { ?>
            <a href="/app/books/return_book.php?id=<?php echo $book['id']; ?>" class="btn btn-return">Return</a>
        <?php } ?>
    </div>
</div>
<?php
    }
} else {
    echo "<p>No books found.</p>";
}
?>


        </div>

            </div>

            <!-- Footer -->
            <footer class="footer" style="margin-top: 40px; text-align: center; background-color: #333; padding: 10px; box-shadow: 0px -5px 15px rgba(0,0,0,0.1);">
                <p>&copy; 2024 Library System. All Rights Reserved.</p>
            </footer>

</body>
</html>