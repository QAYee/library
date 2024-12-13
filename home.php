<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
require_once(ROOT_DIR."/includes/header.php");
require_once(ROOT_DIR.'/app/config/DatabaseConnect.php');
session_start();

// Initialize the database connection
$db = new DatabaseConnect();
$conn = $db->connectDB();

$query = "SELECT id, title, description, author, category, ISBN, copies, image_path FROM books";
$result = mysqli_query($conn, $query);

// Check if the query ran successfully
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
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
          body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7fc;
        margin: 0;
        
        color: #333;
        }

        h3 {
        color: #3a6ea5;
        font-weight: bold;
        font-size: 24px;
        margin-bottom: 30px;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1px;
        background: linear-gradient(to right, #3a6ea5, #5a9bd4);
        -webkit-background-clip: text;
        color: transparent;
        }

        .book-list {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
        max-width: 900px;
        margin: 0 auto;
        overflow: hidden;
        }

        ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
        }

        li {
        position: relative;
        margin-bottom: 20px;
        padding-left: 30px;
        font-size: 18px;
        line-height: 1.6;
        border-left: 3px solid #3a6ea5;
        transition: all 0.3s ease;
        }

        li:hover {
        background-color: #f1f5fa;
        transform: scale(1.02);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        li:hover .book-title {
        color: #5a9bd4;
        }

        .book-title {
        font-weight: bold;
        color: #3a6ea5;
        font-size: 20px;
        }

        .book-description {
        font-size: 16px;
        color: #777;
        margin-top: 5px;
        }

        .book-meta {
        font-size: 16px;
        color: #555;
        margin-top: 5px;
        display: flex;
        justify-content: space-between;
        }

        .book-meta span {
        font-weight: 600;
        color: #3a6ea5;
        }

        /* Hover effect for links */
        a {
        text-decoration: none;
        color: #3a6ea5;
        }

        a:hover {
        color: #5a9bd4;
        text-decoration: underline;
        }

        @media (max-width: 768px) {
        .book-list {
            padding: 20px;
        }

        h3 {
            font-size: 22px;
            margin-bottom: 20px;
        }

        li {
            font-size: 16px;
        }
        }
        /* Overall Container */
        .book-cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Adjust spacing between cards */
            justify-content: center; /* Center align cards */
            padding: 20px;
        }

        /* Individual Book Card */
        .book-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 6px 18px rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: calc(25% - 20px); /* Adjust card width dynamically */
            max-width: 270px;
            height: auto;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Book Image */
        .book-card img {
            width: 100%;
            height: 220px; /* Maintain aspect ratio */
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        /* Book Title */
        .book-card h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
            text-align: center;
        }

        /* Book Description */
        .book-card p {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Limit to 3 lines */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
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

        .btn-borrow {
            background-color: #1976D2;
            color: white;
        }

        .btn-borrow:hover {
            background-color: #1259a1;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .book-card {
                width: calc(50% - 20px); /* Adjust card width for medium screens */
            }
        }

        @media screen and (max-width: 480px) {
            .book-card {
                width: calc(100% - 20px); /* Adjust card width for small screens */
            }
        }
        
    </style>
</head>
<body>
    <header class="header">
        <h1>Library System</h1>
    </header>

    <?php require_once(ROOT_DIR."/includes/navbar.php");?>

    <main class="main">
        <section class="categories">
            <h2>Book Categories</h2>
            <div class="category-container">
                <button class="category-button">
                    <h3>Fiction</h3>
                    <p>Explore our collection of fiction books.</p>
                </button>
                <button class="category-button">
                    <h3>Non-Fiction</h3>
                    <p>Discover educational and insightful reads.</p>
                </button>
                <button class="category-button">
                    <h3>Science</h3>
                    <p>Dive into the world of science and research.</p>
                </button>
                <button class="category-button">
                    <h3>History</h3>
                    <p>Learn about past events and historical figures.</p>
                </button>
                <button class="category-button">
                    <h3>Art</h3>
                    <p>Discover artistic masterpieces and more.</p>
                </button>
                <button class="category-button">
                    <h3>Technology</h3>
                    <p>Explore books on technological advancements.</p>
                </button>
            </div>
        </section>

        <section class="popular-books">
            <h3>Popular Books</h3>
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
                        $bookImage = isset($book['image_path']) ? htmlspecialchars($book['image_path']) : 'default-image.jpg';
                ?>
                <div class="book-card">                   
                    <img src="<?php echo $bookImage; ?>" alt="Book Image">
                    <h3 class="book-title"><?php echo $bookTitle; ?></h3>
                    <p><strong>Description:</strong> <?php echo $bookDescription; ?></p>
                    <p><strong>Author:</strong> <?php echo $bookAuthor; ?></p>
                    <p><strong>Category:</strong> <?php echo $bookCategory; ?></p>
                    <p><strong>ISBN:</strong> <?php echo $bookIsbn; ?></p>
                    <p><strong>Copies Available:</strong> <?php echo $bookCopies; ?></p>
                </div>
                <?php
                    }
                } else {
                    echo "<p>No books found.</p>";
                }
                ?>
                
            </div>
            
            <div>
            <?php
    // Initialize the database connection
    $db = new DatabaseConnect();
    $conn = $db->connectDB();

    if (!$conn) {
        echo "Database connection failed.";
    } else {
        try {
            // Fetch the count of books in the books table
            $booksCountQuery = "
                SELECT COUNT(*) AS books_count
                FROM books";
            $booksCountResult = mysqli_query($conn, $booksCountQuery);
            $booksCount = 0;

            if ($booksCountResult && mysqli_num_rows($booksCountResult) > 0) {
                $booksData = mysqli_fetch_assoc($booksCountResult);
                $booksCount = isset($booksData['books_count']) ? (int)$booksData['books_count'] : 0;
            }

            echo "<h3>Total Books: {$booksCount}</h3>";
            echo"<div><h3>Top Five Burrowed Book</h3></div>";

            // Fetch top 5 popular books based on total borrows
            $popularBooksQuery = "
                SELECT books.id, books.title, books.author, COUNT(transactions.book_id) AS total_borrows
                FROM books
                LEFT JOIN transactions ON books.id = transactions.book_id
                GROUP BY books.id
                ORDER BY total_borrows DESC
                LIMIT 5";
            $popularBooksResult = mysqli_query($conn, $popularBooksQuery);

            if ($popularBooksResult && mysqli_num_rows($popularBooksResult) > 0) {
                echo "<ul>";
                // Loop through and display the results
                while ($book = mysqli_fetch_assoc($popularBooksResult)) {
                    $bookTitle = isset($book['title']) ? htmlspecialchars($book['title']) : 'No Title';
                    $bookAuthor = isset($book['author']) ? htmlspecialchars($book['author']) : 'No Author';
                    $bookBorrows = isset($book['total_borrows']) ? (int)$book['total_borrows'] : 0;
                    echo "<li>{$bookTitle} by {$bookAuthor} - Borrowed {$bookBorrows} times</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No popular books found.</p>";
            }
        } catch (Exception $e) {
            echo "Error fetching data: " . $e->getMessage();
        }
    }
?>

    <style>
  
  </style>
</div>

        </section>
    </main>

    <?php require_once(ROOT_DIR."/includes/footer.php")?>
</body>
</html>
