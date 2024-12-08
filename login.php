<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
require_once(ROOT_DIR."/includes/header.php");
require_once(ROOT_DIR.'/app/config/DatabaseConnect.php');
session_start();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body style="margin: 0; padding: 0; height: 100vh; font-family: Arial, sans-serif; background-color: #e2e2e2;">

    <!-- Header -->
    <header class="header">
        <h1>Library System</h1>
    </header>

    <!-- Navbar -->
    <?php require_once(ROOT_DIR."/includes/navbar.php");?>

    <!-- Main Content (Login Form) -->
    <div style="display: flex; justify-content: center; align-items: center; height: 70vh;">
        <div style="background-color:#FFFFFF; padding: 40px; border-radius: 10px; width: 100%; max-width: 400px; color: #333; box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1); text-align: center;">
            <h2 style="font-size: 24px; margin-bottom: 30px; color: #a33b3b;">Login</h2>

            <!-- Display error message -->
            <?php



            if (isset($_SESSION['login_error'])) {
                echo '<div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">' . $_SESSION['login_error'] . '</div>';
                unset($_SESSION['login_error']); // Clear the error after displaying it
            }
            ?>

            <!-- Login Form -->
            <form action="/app/auth/login_action.php" method="post">
                <input type="text" name="username" placeholder="Username" required style="width: 100%; padding: 12px; margin: 10px 0; border: none; border-radius: 5px; font-size: 16px; background-color: #e2e2e2; color: #333;">
                <input type="password" name="password" placeholder="Password" required style="width: 100%; padding: 12px; margin: 10px 0; border: none; border-radius: 5px; font-size: 16px; background-color: #e2e2e2; color: #333;">
                <button type="submit" style="padding: 12px 20px; background-color: #a33b3b; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; width: 100%; margin-top: 10px;">Login</button>
            </form>

            <div style="margin-top: 20px;">
                <p style="color: #a33b3b;">Don't have an account? <a href="register.php" style="color: #1976D2; text-decoration: none;">Sign up</a></p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php require_once(ROOT_DIR."/includes/footer.php")?>