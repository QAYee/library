<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/Directories.php");
require_once(ROOT_DIR . "/includes/header.php");
require_once(ROOT_DIR . "/app/config/DatabaseConnect.php");
session_start();
$db = new DatabaseConnect();
$conn = $db->connectDB();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];  // Get the logged-in user's username
} else {
    $username = 'Guest';  // Default value if user is not logged in
}

$query = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; // Sanitize user input

if ($query) {
    $searchQuery = "SELECT * FROM books 
                    WHERE title LIKE '%$query%' 
                       OR author LIKE '%$query%' 
                       OR category LIKE '%$query%' 
                       OR ISBN LIKE '%$query%'";
    $result = mysqli_query($conn, $searchQuery);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        /* Header Styling */
        .header {
            text-align: center;
            background-color: #a33b3b;
            color: white;
            padding: 20px 0;
            border-bottom: 4px solid #8a2c2c;
        }

        .header h1 {
            margin: 0;
            font-size: 36px;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 16px;
        }

        /* Navbar Styling */
        .navbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            background-color: #eee;
            padding: 10px;
        }

        .navbar a {
            color: #333;
            text-decoration: none;
            padding: 10px 15px;
            margin-left: 10px;
        }

        .navbar a:hover {
            color: red;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropbtn {
            background-color: transparent;
            color: #333;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            border: none;
        }

        .dropbtn:hover {
            color: red;
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
        }

        .dropdown-content a {
            color: #333;
            padding: 10px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f2f2f2;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Book Card Styling */
        .book-card {
            background-color: white;
            margin: 20px auto;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            display: flex;
            align-items: center;
        }

        .book-card img {
            width: 120px;
            height: auto;
            border-radius: 5px;
            margin-right: 20px;
        }

        .book-details {
            flex: 1;
        }

        .book-title {
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0;
        }

        .book-meta {
            margin: 5px 0;
            color: #666;
        }

        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #a33b3b;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #8a2c2c;
        }

        /* Responsive Design */
        @media screen and (max-width: 600px) {
            .book-card {
                flex-direction: column;
                text-align: center;
            }

            .book-card img {
                margin: 0 auto 15px;
            }
        }
        .btn-back {
            display: inline-block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #555;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            text-align: center;
        }

        .btn-back:hover {
            background-color: #333;
        }

    </style>
</head>

<body>
    <header class="header">
        <div class="container">
            <h1>Welcome to the Library</h1>
            <p>Search for books, authors, and categories!</p>
        </div>
    </header>

    <nav class="navbar">
        <a href="<?php echo BASE_URL; ?>user_home.php">Home</a>
        <a href="<?php echo BASE_URL; ?>views/user/book_status.php">Borrowed Books</a>
        <div class="dropdown">
            <button class="dropbtn">
                <?php echo htmlspecialchars($username); ?> ▼
            </button>
            <div class="dropdown-content">
                <a href="profile_admin.php">Profile</a>
                <a href="dashboard_admin.php">Dashboard</a>
                <a href="<?php echo BASE_URL; ?>logout.php" style="color: red;">Logout</a>
            </div>
        </div>
    </nav>

    <h1>Search Results</h1>
    <a href="<?php echo BASE_URL; ?>user_home.php" class="btn-back">Back to Home</a>

    <?php if ($query): ?>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($book = mysqli_fetch_assoc($result)): ?>
                <div class="book-card">
                    <a href="/app/books/history.php?id=<?php echo $book['id']; ?>">
                        <img src="<?php echo $book['image_path'] ?: 'default-image.jpg'; ?>" alt="Book Image">
                    </a>
                    <div class="book-details">
                        <p class="book-title"><?php echo htmlspecialchars($book['title']); ?></p>
                        <p class="book-meta"><strong>Author:</strong> <?php echo htmlspecialchars($book['author'] ?: 'Unknown'); ?></p>
                        <p class="book-meta"><strong>Category:</strong> <?php echo htmlspecialchars($book['category'] ?: 'General'); ?></p>
                        <p class="book-meta"><strong>ISBN:</strong> <?php echo htmlspecialchars($book['ISBN'] ?: 'N/A'); ?></p>
                        <a href="/app/books/borrow_book.php?id=<?php echo $book['id']; ?>" class="btn">Borrow</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No books found matching "<?php echo $query; ?>".</p>
        <?php endif; ?>
    <?php else: ?>
        <p>Please enter a search term.</p>
    <?php endif; ?>
</body>
</html>