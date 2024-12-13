<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
require_once(ROOT_DIR."/includes/header.php");
require_once(ROOT_DIR.'/app/config/DatabaseConnect.php');

session_start();
// Initialize the database connection
$db = new DatabaseConnect();
$conn = $db->connectDB();


?>
<?php require_once(ROOT_DIR."/views/components/page-guard.php"); ?> 
<!DOCTYPE html>
<html lang="en">
<head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Panel</title>
            <link rel="stylesheet" href="styles.css">
     <style>

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
        .add-book-btn {
                    display: inline-block;
                    padding: 12px 20px;
                    background-color: #a33b3b; 
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                    font-size: 16px;
                    font-weight: bold;
                    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
                    width: 200px;
                    text-align: center;
                }

                .add-book-btn:hover {
                    background-color: white;
                    color: #a33b3b;
                }

                .add-buttons-container {
                    display: flex;
                    gap: 10px;
                    justify-content: center;
                    margin-bottom: 20px;
                }

        .book-cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; 
            justify-content: center; 
            padding: 20px;
        }

        .book-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 6px 18px rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: 270px; 
            height: 500px; 
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .book-card img {
            width: 100%;
            height: 220px; 
            object-fit: contain; 
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .book-card h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .book-card p {
            font-size: 12px;
            color: #555;
            margin-bottom: 2px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
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

        .btn-container {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 10px;
        }

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

        .btn-edit {
            background-color: #1976D2;
            color: white;
        }

        .btn-delete {
            background-color: #d9534f;
            color: white;
        }

        .btn-history {
            background-color: #5cb85c;
            color: white;
        }

        .book-cards-container {
            margin-right: 0;
            justify-content: center;
        }

    </style>
</head>
 <!-- Header -->
 <header class="header">
        <h1>Library System</h1>
    </header>

    <!-- Navbar -->
<nav class="navbar">
    <a href="<?php echo BASE_URL; ?>admin_home.php">Manage Books</a>
    <a href="<?php echo BASE_URL; ?>views/admin/book_request.php">Requests</a> <!-- Book Request Link -->
    <a href="<?php echo BASE_URL; ?>views/admin/transactions.php">Transactions</a>
    <div class="dropdown">
        <button class="dropbtn">
            <?php echo isset($_SESSION["username"]) ? htmlspecialchars($_SESSION["username"]) : 'Guest'; ?> ▼
        </button>
        <div class="dropdown-content">
            <a href="<?php echo BASE_URL; ?>logout.php" style="color: red;">Logout</a>
        </div>
    </div>
</nav>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; padding-bottom: 40px; background-color: #e2e2e2;">

    <!-- Main Content -->
    <div class="container" style="padding: 20px;">
        <!-- Search Bar -->
        <div style="margin-bottom: 20px;">
            <form action="<?php echo BASE_URL;?>views/admin/search_books.php" method="get">
                <input type="text" name="query" placeholder="Search Books" required style="width: calc(100% - 110px); padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px;">
                <button type="submit" style="padding: 10px 20px; background-color: #a33b3b; color: white; border: none; border-radius: 5px; cursor: pointer;">Search</button>
            </form>
        </div>

        
        <!-- Add New Buttons Section -->
        <div class="add-buttons-container">
            <a href="<?php echo BASE_URL;?>views/admin/add_book.php" class="add-book-btn">Add New Book</a>
            <!-- <a href="<?php echo BASE_URL;?>views/admin/add_category.php" class="add-book-btn">Add New Category</a> -->
        </div>

        <!-- Book Categories -->
   


    <div class="book-cards-container">
    <?php
// Initialize the database connection
$db = new DatabaseConnect();
$conn = $db->connectDB();

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch books from the database
$query = "SELECT id, title, description, author, category, ISBN, copies, image_path, total_borrows FROM books";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    // Loop through the books and display them
    while ($book = mysqli_fetch_assoc($result)) {
        // Use ternary operators to check if the value exists
        $bookTitle = isset($book['title']) ? htmlspecialchars($book['title']) : 'No Title';
        $bookDescription = isset($book['description']) ? htmlspecialchars($book['description']) : 'No Description';
        $bookAuthor = isset($book['author']) ? htmlspecialchars($book['author']) : 'No Author';
        $bookCategory = isset($book['category']) ? htmlspecialchars($book['category']) : 'No Category';
        $bookIsbn = isset($book['ISBN']) ? htmlspecialchars($book['ISBN']) : 'No ISBN';
        $bookCopies = isset($book['copies']) ? htmlspecialchars($book['copies']) : '0';
        $bookTotalBorrows = isset($book['total_borrows']) ? htmlspecialchars($book['total_borrows']) : '0';
        $bookImage = isset($book['image_path']) ? htmlspecialchars($book['image_path']) : 'default-image.jpg'; // Default image if missing
?>
        <!-- Book Card -->
        <div class="book-card">
            <img src="<?php echo $bookImage; ?>" alt="Book Image">
            <h3 class="book-title"><?php echo $bookTitle; ?></h3>
            <p><strong>Description:</strong> <?php echo $bookDescription; ?></p>
            <p><strong>Author:</strong> <?php echo $bookAuthor; ?></p>
            <p><strong>Category:</strong> <?php echo $bookCategory; ?></p>
            <p><strong>ISBN:</strong> <?php echo $bookIsbn; ?></p>
            <p><strong>Copies Available:</strong> <?php echo $bookCopies; ?></p>
            <div class="btn-container">
                <a href="<?php echo BASE_URL;?>views/admin/edit_book.php?id=<?php echo $book['id']; ?>" class="btn btn-edit">Edit</a>
                <a href="<?php echo BASE_URL;?>app/books/delete_book.php?id=<?php echo $book['id']; ?>" class="btn btn-delete">Delete</a>
                <a href="<?php echo BASE_URL;?>views/admin/history.php?id=<?php echo $book['id']; ?>" class="btn btn-history">History</a>
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
      <?php require_once(ROOT_DIR."/includes/footer.php")?>