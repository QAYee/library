<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/Directories.php");
require_once(ROOT_DIR . "/includes/header.php");
require_once(ROOT_DIR . '/app/config/DatabaseConnect.php');

$db = new DatabaseConnect();
$conn = $db->connectDB();

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];  
    } else {
        $username = 'Admin';  
    }
    ?>

    <!-- navbar.php -->
    <nav class="navbar">
    <a href="<?php echo BASE_URL; ?>admin_home.php">Manage Books</a>
    <a href="<?php echo BASE_URL; ?>views/user/book_request.php">Requests</a> <!-- Book Request Link -->
    <a href="<?php echo BASE_URL; ?>views/admin/transactions.php">Transactions</a>
    <div class="dropdown">
        <button class="dropbtn">
            <?php echo isset($_SESSION["username"]) ? htmlspecialchars($_SESSION["username"]) : 'Guest'; ?> ▼
        </button>
        <div class="dropdown-content">
            <a href="<?php echo BASE_URL; ?>home.php" style="color: red;">Logout</a>
        </div>
    </div>
</nav>
<style>
    
    .navbar {
        display: flex;
        justify-content: flex-end; 
        align-items: center;
        background-color: #eee;
        color: black;
        padding: 1px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .navbar a {
        color: #333; 
        text-decoration: none;
        padding: 10px;
        margin-left: 20px;
        font-size: 16px;
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
        outline: none; 
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
</style>
