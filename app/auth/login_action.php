<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");

// Collect user input
$username = $_POST["username"];
$password = $_POST["password"];

include('../config/DatabaseConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new DatabaseConnect();
    $conn = $db->connectDB();

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    try {
        // Prepare the query to check if the user exists
        $stmt = $conn->prepare('SELECT * FROM `users` WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // Verify the password
            if (password_verify($password, $user["password"])) {
                // Regenerate session and set session variables
                $_SESSION = [];
                session_regenerate_id(true);
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["is_admin"] = $user["is_admin"]; // Maintain is_admin check

                // Redirect based on is_admin
                if ($user["is_admin"] == 1) { // Admin user
                    header("location: ../../admin_home.php");
                } else { // Regular user
                    header("location: ../../user_home.php");
                }
                exit();
            } else {
                // Incorrect password
                $_SESSION["login_error"] = "Invalid password!";
                header("location: ../../login.php");
                exit();
            }
        } else {
            // No user found
            $_SESSION["login_error"] = "No user found!";
            header("location: ../../login.php");
            exit();
        }
    } catch (Exception $e) {
        echo "Connection Failed: " . $e->getMessage();
    }
}
?>
