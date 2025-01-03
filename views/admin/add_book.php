<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
require_once(ROOT_DIR."/includes/header.php");
require_once(ROOT_DIR.'/app/config/DatabaseConnect.php');

session_start();
// Initialize the database connection

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';

?>
 <?php require_once(ROOT_DIR."/views/components/page-guard.php"); ?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
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
        .form-group input,
        .form-group select,
        .form-group textarea {
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

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #e2e2e2;">

    <!-- Header -->
    <header class="header" style="text-align: center; background-color: #a33b3b; color: white; padding: 10px;">
        <h1>Library System - Admin Panel</h1>
    </header>

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

    <!-- Main Content -->
    <div class="form-container">
        <h2>Add New Book</h2>
        <form action="/app/books/add.php" method="POST" enctype="multipart/form-data">
            

        <script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = "uploads/default-image.jpg"; // Reset to default if no file is selected
        }
    }
</script>


        <div class="form-group">
    <label for="image_path">Book Cover</label>
    <input type="file" name="image_path" id="image_path" accept="image/*" onchange="previewImage(event)">
    <div id="image_preview" style="margin-top: 10px;">
        <img id="preview" src="uploads/default-image.jpg" alt="Image Preview" style="width: 100%; max-height: 200px; object-fit: contain;">
    </div>
</div>




            

            <div class="form-group">
                <label for="title">Book Title</label>
                <input type="text" name="title" id="title" placeholder="Enter book title" required>
            </div>
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" name="author" id="author" placeholder="Enter author name" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select name="category" id="category" required>
                    <option value="Fiction">Fiction</option>
                    <option value="Non-Fiction">Non-Fiction</option>
                    <option value="Science">Science</option>
                    <option value="History">History</option>
                    <option value="Art">Art</option>
                    <option value="Technology">Technology</option>
                    <option value="Biography">Biography</option>
                    <option value="Mystery">Mystery</option>
                    <option value="Fantasy">Fantasy</option>
                    <option value="Romance">Romance</option>
                    <option value="Horror">Horror</option>
                    <option value="Self-Help">Self-Help</option>
                    <option value="Poetry">Poetry</option>
                </select>

            </div>
            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input type="text" name="isbn" id="isbn" placeholder="Enter ISBN" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4" placeholder="Enter book description" required></textarea>
            </div>
            <div class="form-group">
                <label for="copies">Number of Copies</label>
                <input type="number" name="copies" id="copies" placeholder="Enter number of copies" required>
            </div>
            <div class="form-actions">
                <button type="submit">Save Book</button>
                <a href="<?php echo BASE_URL; ?>admin_home.php">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="footer" style="margin-top: 40px; text-align: center; background-color: #333; padding: 10px;">
        <p>&copy; 2024 Library System. All Rights Reserved.</p>
    </footer>

</body>
</html>
