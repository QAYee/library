<?php
include('includes/db.php');
session_start();

// Collect user input
$username = $_POST['username'];
$password = $_POST['password'];

// Check if user exists
$result = $conn->query("SELECT * FROM users WHERE username='$username'");
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Verify password
    if (password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin']; // New is_admin check
        $_SESSION['username'] = $user['username'];

        // Redirect based on is_admin
        if ($user['is_admin'] == 1) { // Admin user
            header('Location: pages/admin_home.php');
        } else { // Regular user
            header('Location: pages/user_home.php');
        }
        exit();
    } else {
        // Incorrect password
        $_SESSION['login_error'] = "Invalid password!";
        header('Location: pages/login.php');
        exit();
    }
} else {
    // No user found
    $_SESSION['login_error'] = "No user found!";
    header('Location: pages/login.php');
    exit();
}
?>
