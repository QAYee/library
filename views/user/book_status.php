<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
require_once(ROOT_DIR.'/app/config/DatabaseConnect.php');
session_start();

$db = new DatabaseConnect();
$conn = $db->connectDB();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];  // Get the logged-in user's username
} else {
    $username = 'Guest';  // Default value if user is not logged in
}

// Query to fetch user's borrowed books
$query = "SELECT br.book_id, br.request_date, br.status, br.estimated_return_date, br.actual_return_date, br.penalty_amount, b.title
          FROM book_requests br 
          JOIN books b ON br.book_id = b.id
          WHERE br.username = '$username'";  // Only fetch the books that the user has borrowed

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
    <title>My Book Requests</title>
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

        .header {
            text-align: center;
            background-color: #a33b3b;
            color: white;
            padding: 1px 0; 
        }
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
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
                <a href="<?php echo BASE_URL; ?>user_home.php">Home</a>
                <a href="<?php echo BASE_URL; ?>views/user/book_status.php">Borrowed Books</a> <!-- Book Request Link -->
                <div class="dropdown">
                    <button class="dropbtn">
                        <?php echo htmlspecialchars($username); ?> ▼
                    </button>
                    <div class="dropdown-content">
                        <a href="profile_admin.php">Profile</a>
                        <a href="dashboard_admin.php">Dashboard</a>
                        <a href="home.php" style="color: red;">Logout</a>
                    </div>
                </div>
            </nav>

    <!-- Main Content -->
    <div style="padding: 20px;">
        <h2>Books You Have Borrowed</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Request Date</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Actual Return Date</th>
                        <th>Penalty Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['request_date'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['status'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['estimated_return_date'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['actual_return_date'] ?? 'Not Returned Yet'); ?></td>
                            <td><?php echo htmlspecialchars($row['penalty_amount'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have not borrowed any books yet.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer style="background-color: #333; color: white; text-align: center; padding: 10px; position: fixed; bottom: 0; width: 100%;">
        <p>&copy; 2024 Library System. All Rights Reserved.</p>
    </footer>

</body>
</html>
