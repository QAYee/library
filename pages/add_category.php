<?php
include('../includes/header.php');
include('../includes/db.php');
include('../includes/session_start.php');

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

    // Insert new category into the database
    $query = "INSERT INTO categories (name) VALUES ('$category_name')";

    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Category added successfully!"); window.location.href="admin_home.php";</script>';
    } else {
        echo '<script>alert("Failed to add category: ' . mysqli_error($conn) . '");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Category</title>
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

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .form-container {
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .form-actions button,
        .form-actions a {
            width: 48%;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            color: white;
            text-decoration: none;
        }
        .form-actions button {
            background-color: #a33b3b;
        }
        .form-actions a {
            background-color: #555;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header" style="text-align: center; background-color: #a33b3b; color: white; padding: 10px;">
        <h1>Library System - Admin Panel</h1>
    </header>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="admin_home.php">Manage Books</a>
        <a href="add_book.php">Add Book</a>
        <a href="add_category.php">Add Category</a>
        <a href="transaction.php">Transactions</a>
        <div class="dropdown">
            <button class="dropbtn">
                <?php echo htmlspecialchars($username); ?> ▼
            </button>
            <div class="dropdown-content">
                <a href="profile_admin.php">Profile</a>
                <a href="dashboard_admin.php">Dashboard</a>
                <a href="logout.php" style="color: red;">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="form-container">
        <h2>Add New Category</h2>
        <form action="add_category.php" method="POST">
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" name="category_name" id="category_name" placeholder="Enter category name" required>
            </div>
            <div class="form-actions">
                <button type="submit">Save Category</button>
                <a href="admin_home.php">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="footer" style="margin-top: 168px; text-align: center; background-color: #333; padding: 10px;">
        <p>&copy; 2024 Library System. All Rights Reserved.</p>
    </footer>
</body>
</html>
