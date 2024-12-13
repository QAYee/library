<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/Directories.php");
require_once(ROOT_DIR . "/includes/header.php");
require_once(ROOT_DIR . '/app/config/DatabaseConnect.php');
session_start();

// Initialize database connection
$db = new DatabaseConnect();
$conn = $db->connectDB();
$conn->set_charset("utf8mb4"); // Ensure UTF-8 encoding for handling special characters
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';

// Retrieve book data based on ID
$book_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

// Redirect if the book ID is invalid
if ($book_id <= 0) {
    echo '<script>alert("Invalid book ID!"); window.location.href="admin_home.php";</script>';
    exit;
}

// Fetch the book's current data
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$book = $result->fetch_assoc();

if (!$book) {
    echo '<script>alert("Book not found!"); window.location.href="admin_home.php";</script>';
    exit;
}

// Function to handle image upload
function uploadImage($file, $current_image_path) {
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/pages/uploads/';

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
        $file_name = uniqid() . '-' . basename($file['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            // Delete the old image if it exists and is different from the new one
            if ($current_image_path && realpath($_SERVER['DOCUMENT_ROOT'] . $current_image_path) !== realpath($target_file)) {
                $old_file_path = $_SERVER['DOCUMENT_ROOT'] . $current_image_path;
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }
            return '/pages/uploads/' . $file_name;
        } else {
            echo '<script>alert("Failed to upload image.");</script>';
        }
    }

    return $current_image_path;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $title = isset($_POST['title']) && $_POST['title'] !== ''
        ? mysqli_real_escape_string($conn, $_POST['title'])
        : $book['title'];

    $author = isset($_POST['author']) && $_POST['author'] !== ''
        ? mysqli_real_escape_string($conn, $_POST['author'])
        : $book['author'];

    $category = isset($_POST['category']) && $_POST['category'] !== ''
        ? mysqli_real_escape_string($conn, $_POST['category'])
        : $book['category'];

    $isbn = isset($_POST['isbn']) && $_POST['isbn'] !== ''
        ? mysqli_real_escape_string($conn, $_POST['isbn'])
        : $book['ISBN'];

    // Allow description to accept any text
    $description = isset($_POST['description']) 
        ? mysqli_real_escape_string($conn, $_POST['description']) 
        : $book['description'];

    $copies = isset($_POST['copies']) && is_numeric($_POST['copies'])
        ? (int)$_POST['copies']
        : $book['copies'];

    // Update book details (without image)
    $update_query = "UPDATE books SET 
        title = ?, 
        author = ?, 
        category = ?, 
        ISBN = ?, 
        description = ?, 
        copies = ? 
        WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssssi", $title, $author, $category, $isbn, $description, $copies, $book_id);

    if (!$stmt->execute()) {
        error_log("SQL Error: " . $stmt->error); // Log the error
        die('Error updating book details. Please check server logs.');
    }
    $stmt->close();

    // Handle the image upload separately
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_path = uploadImage($_FILES['image'], $book['image_path']);
        if ($image_path !== $book['image_path']) {
            $image_query = "UPDATE books SET image_path = ? WHERE id = ?";
            $stmt = $conn->prepare($image_query);
            $stmt->bind_param("si", $image_path, $book_id);

            if (!$stmt->execute()) {
                error_log("SQL Error: " . $stmt->error); // Log the error
                die('Error updating book image. Please check server logs.');
            }
            $stmt->close();
        }
    }

    // Redirect to the admin home page on success
    header("Location: ../../admin_home.php");
    exit;
}

$conn->close();
?>











<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Book</title>
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
<body>

<header class="header" style="text-align: center; background-color: #a33b3b; color: white; padding: 10px;">
        <h1>Library System - Admin Panel</h1>
    </header>


    <nav class="navbar">
    <a href="<?php echo BASE_URL; ?>admin_home.php">Manage Books</a>
    <a href="<?php echo BASE_URL; ?>views/user/book_request.php">Requests</a> <!-- Book Request Link -->
    <a href="<?php echo BASE_URL; ?>views/admin/transactions.php">Transactions</a>
    <div class="dropdown">
        <button class="dropbtn">
            <?php echo isset($_SESSION["username"]) ? htmlspecialchars($_SESSION["username"]) : 'Guest'; ?> ▼
        </button>
        <div class="dropdown-content">
            <a href="profile_admin.php">Profile</a>
            <a href="dashboard_admin.php">Dashboard</a>
            <a href="home.php" style="color: red;">Logout</a>
        </div>
    </div>
</nav>

    <!-- Main Content -->
    <div class="form-container">
        <h2>Edit Book</h2>
        <form action="" method="POST" enctype="multipart/form-data">
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
                        preview.src = "<?php echo htmlspecialchars($book['image_path']); ?>";
                    }
                }
            </script>

            <div class="form-group">
                <label for="image">Book Cover</label>
                <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)">
                <div id="image_preview" style="margin-top: 10px;">
                    <img id="preview" src="<?php echo htmlspecialchars($book['image_path']); ?>" alt="Current Image" style="width: 100%; max-height: 200px; object-fit: contain;">
                </div>
            </div>

            <div class="form-group">
                <label for="title">Book Title</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" name="author" id="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select name="category" id="category" required>
                    <option value="Fiction" <?php if ($book['category'] == 'Fiction') echo 'selected'; ?>>Fiction</option>
                    <option value="Non-Fiction" <?php if ($book['category'] == 'Non-Fiction') echo 'selected'; ?>>Non-Fiction</option>
                    <option value="Science" <?php if ($book['category'] == 'Science') echo 'selected'; ?>>Science</option>
                    <option value="History" <?php if ($book['category'] == 'History') echo 'selected'; ?>>History</option>
                    <option value="Art" <?php if ($book['category'] == 'Art') echo 'selected'; ?>>Art</option>
                    <option value="Technology" <?php if ($book['category'] == 'Technology') echo 'selected'; ?>>Technology</option>
                    <option value="Biography" <?php if ($book['category'] == 'Biography') echo 'selected'; ?>>Biography</option>
                    <option value="Mystery" <?php if ($book['category'] == 'Mystery') echo 'selected'; ?>>Mystery</option>
                    <option value="Fantasy" <?php if ($book['category'] == 'Fantasy') echo 'selected'; ?>>Fantasy</option>
                    <option value="Romance" <?php if ($book['category'] == 'Romance') echo 'selected'; ?>>Romance</option>
                    <option value="Horror" <?php if ($book['category'] == 'Horror') echo 'selected'; ?>>Horror</option>
                    <option value="Self-Help" <?php if ($book['category'] == 'Self-Help') echo 'selected'; ?>>Self-Help</option>
                    <option value="Poetry" <?php if ($book['category'] == 'Poetry') echo 'selected'; ?>>Poetry</option>
                </select>

            </div>
            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input type="text" name="isbn" id="isbn" value="<?php echo htmlspecialchars($book['ISBN']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4" required><?php echo htmlspecialchars($book['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="copies">Number of Copies</label>
                <input type="number" name="copies" id="copies" value="<?php echo htmlspecialchars($book['copies']); ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit">Save Changes</button>
                <a href="<?php echo BASE_URL;?>admin_home.php">Cancel</a>
            </div>
        </form>
    </div>

    <footer class="footer" style="margin-top: 40px; text-align: center; background-color: #333; padding: 10px;">
        <p>&copy; 2024 Library System. All Rights Reserved.</p>
    </footer>

</body>
</html>
