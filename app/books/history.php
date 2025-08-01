<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/Directories.php");
require_once(ROOT_DIR."/includes/header_admin.php");
require_once(ROOT_DIR . '/app/config/DatabaseConnect.php');
session_start();
$db = new DatabaseConnect();
$conn = $db->connectDB();

if (!isset($_SESSION['username'])) {
    header('Location: user_home.php');
    exit;
}

// Get the book_id from the URL
$book_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : null;

if ($book_id) {
    // Fetch book details
    $book_query = "SELECT * FROM books WHERE id = '$book_id' LIMIT 1";
    $stmt = $conn->prepare("SELECT COUNT(*) AS borrow_count FROM transactions WHERE book_id = ?");
    $stmt->bind_param("s", $book_id); // Assuming $book_id is a string
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $borrow_count = $row['borrow_count']; // Fetch the count value
    

    $book_result = mysqli_query($conn, $book_query);

    if (!$book_result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $book = mysqli_fetch_assoc($book_result);

    if (!$book) {
        echo "<p>Book not found.</p>";
        exit;
    }

    // Fetch transaction history for the specific book including username
    $transaction_query = "SELECT t.transaction_id, t.user_id, u.username, t.status, t.borrow_date, t.return_date, t.penalty, b.title
                          FROM transactions t
                          JOIN books b ON t.book_id = b.id
                          JOIN users u ON t.user_id = u.id
                          WHERE t.book_id = '$book_id'
                          ORDER BY t.borrow_date DESC";
    
    $transaction_result = mysqli_query($conn, $transaction_query);

    if (!$transaction_result) {
        die("Query failed: " . mysqli_error($conn));
    }
} else {
    echo "<p>No book ID provided.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details and Transaction History</title>
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
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        /* Book Card */
        .book-card {
            background-color: #fff;
            padding: 30px;
            margin: 20px 0;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }
        .book-card img {
            max-width: 250px;
            margin-right: 30px;
        }
        .book-card h3 {
            margin: 0;
            font-size: 32px;
            font-weight: bold;
        }
        .book-card p {
            font-size: 16px;
            margin: 5px 0;
        }
        .book-card p.description {
            flex-grow: 1;
            max-height: 100px; /* Set maximum height for the description */
            overflow-y: auto; /* Add vertical scrollbar if content overflows */
        }
        /* Transaction History Card */
        .history-card {
            background-color: #fff;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .history-card h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        .transaction-table th, .transaction-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .transaction-table th {
            background-color: #333;
            color: white;
        }
        .transaction-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .transaction-table tr:hover {
            background-color: #f1f1f1;
        }
        .btn {
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-edit {
            background-color: #4CAF50;
            color: white;
        }
        .btn-delete {
            background-color: #f44336;
            color: white;
        }
        footer {
            margin-top: 50px;
            text-align: center;
            background-color: #333;
            color: white;
            padding: 10px;
        }
    </style>
</head>
<body>
<nav class="navbar">
                <a href="<?php echo BASE_URL; ?>user_home.php">Home</a>
                <a href="<?php echo BASE_URL; ?>views/user/book_status.php">Borrowed Books</a> <!-- Book Request Link -->
                <div class="dropdown">
                    <button class="dropbtn">
                    <?php echo isset($_SESSION["username"]) ? htmlspecialchars($_SESSION["username"]) : 'Guest'; ?> ▼
                    </button>
                    <div class="dropdown-content">
                        <a href="<?php echo BASE_URL; ?>logout.php" style="color: red;">Logout</a>
                    </div>
                </div>
            </nav>
    <h1>Book Details and Transaction History</h1>

    <!-- Book Information Card -->
    <div class="book-card">
        <div style="flex-shrink: 0;">
            <img src="<?php echo htmlspecialchars($book['image_path']) ?: 'default-image.jpg'; ?>" alt="Book Image">
        </div>
        <div style="flex-grow: 1;">
            <h3><?php echo htmlspecialchars($book['title']); ?></h3>
            <p class="description"><strong>Description:</strong> <?php echo htmlspecialchars($book['description']) ?: 'No Description'; ?></p>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']) ?: 'No Author'; ?></p>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($book['category']) ?: 'No Category'; ?></p>
            <p><strong>ISBN:</strong> <?php echo htmlspecialchars($book['ISBN']) ?: 'No ISBN'; ?></p>
            <p><strong>Copies Available:</strong> <?php echo htmlspecialchars($book['copies']) ?: '0'; ?></p>
            <p><strong>Total Borrows:</strong> <?php echo htmlspecialchars($borrow_count) ?: '0'; ?></p>
            <div>
            </div>
        </div>
    </div>

    <!-- Transaction History Card -->
    <div class="history-card">
        <h2>Transaction History</h2>
        <?php if (mysqli_num_rows($transaction_result) > 0): ?>
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Borrow Date</th>
                        <th>Return Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($transaction = mysqli_fetch_assoc($transaction_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['username']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['borrow_date']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['return_date'] ?: 'Not Returned'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No transaction history found for this book.</p>
        <?php endif; ?>
    </div>

    <?php require_once(ROOT_DIR."/includes/footer.php") ?>
</body>
</html>
