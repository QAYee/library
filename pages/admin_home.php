<?php
include('header_admin.php');
include('navbar_admin.php');
include('../includes/db.php');
include('../includes/session_start.php');

        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];  // Get the logged-in user's username
        } else {
            $username = 'Admin';  // Default value if user is not logged in
        }
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'User'; // User role (Admin, User)

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
            <title>Admin Panel</title>
            <link rel="stylesheet" href="styles.css">
            <style>

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

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; padding-bottom: 40px; background-color: #e2e2e2;">

    <!-- Main Content -->
    <div class="container" style="padding: 20px;">
        <!-- Search Bar -->
        <div style="margin-bottom: 20px;">
            <form action="search_books.php" method="get">
                <input type="text" name="query" placeholder="Search Books" required style="width: calc(100% - 110px); padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px;">
                <button type="submit" style="padding: 10px 20px; background-color: #a33b3b; color: white; border: none; border-radius: 5px; cursor: pointer;">Search</button>
            </form>
        </div>

        
        <!-- Add New Buttons Section -->
        <div class="add-buttons-container">
            <a href="add_book.php" class="add-book-btn">Add New Book</a>
            <a href="add_category.php" class="add-book-btn">Add New Category</a>
        </div>

        <!-- Book Categories -->
        <div>
        <h2 style="margin-bottom: 10px; color: #a33b3b;">Categories</h2>
        <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;">
            <button style="padding: 10px 20px; background-color: #a33b3b; color: white; border: none; border-radius: 5px;">Fiction</button> <!-- Dark Blue -->
            <button style="padding: 10px 20px; background-color: #a33b3b; color: white; border: none; border-radius: 5px;">Non-Fiction</button> <!-- Dark Green -->
            <button style="padding: 10px 20px; background-color: #a33b3b; color: white; border: none; border-radius: 5px;">Science</button> <!-- Dark Orange -->
            <button style="padding: 10px 20px; background-color: #a33b3b; color: white; border: none; border-radius: 5px;">History</button> <!-- Dark Red -->
            <button style="padding: 10px 20px; background-color: #a33b3b; color: white; border: none; border-radius: 5px;">Art</button> <!-- Dark Purple -->
            <button style="padding: 10px 20px; background-color: #a33b3b; color: white; border: none; border-radius: 5px;">Technology</button> <!-- Dark Yellow -->
        </div>
    </div>


    <div class="book-cards-container">
    <?php
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
            <p><strong>Total Borrows:</strong> <?php echo $bookTotalBorrows; ?></p>
            <div class="btn-container">
                <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="btn btn-edit">Edit</a>
                <a href="delete_book.php?id=<?php echo $book['id']; ?>" class="btn btn-delete">Delete</a>
                <a href="book_history.php?id=<?php echo $book['id']; ?>" class="btn btn-history">History</a>
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

    <?php include('footer.php'); ?>

</body>
</html>
