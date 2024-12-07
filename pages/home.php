<?php
include('../includes/session_start.php');
include('../includes/header.php');
include('../includes/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <h1>Library System</h1>
    </header>

    <nav class="navbar">
        <a href="home.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
        <a href="#categories">Categories</a>
    </nav>

    <main class="main">
        <section class="search-bar">
            <input type="text" placeholder="Search for books..." class="search-input">
            <button class="search-button">Search</button>
        </section>

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
            <ul>
        <?php
        // Fetching top 5 popular books based on total borrows
        $result = $conn->query("SELECT * FROM books ORDER BY total_borrows DESC LIMIT 5");
        while ($book = $result->fetch_assoc()) {
            echo "<li>{$book['title']} by {$book['author']} - Borrowed {$book['total_borrows']} times</li>";
        }
        ?>
            </ul>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2024 Library System. All rights reserved.</p>
    </footer>
</body>
</html>


