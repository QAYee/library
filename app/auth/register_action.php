<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect and sanitize user input
    $fullname = trim($_POST["full_name"]);
    $username = trim($_POST["username"]);
    $course = trim($_POST["course"]);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirm_password"]);

    // Validate if passwords match
    if ($password !== $confirmPassword) {
        $_SESSION["error"] = "Passwords do not match!";
        header("Location: pages/register.php");
        exit();
    }

    // Database connection parameters
    $host = "localhost";
    $dbname = "newlibrary";
    $dbUsername = "root"; // Updated variable name for clarity
    $dbPassword = "";     // Updated variable name for clarity

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    try {
        // Establish database connection using PDO
        $conn = new PDO($dsn, $dbUsername, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the username already exists
        $checkStmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $checkStmt->bindParam(":username", $username);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            $_SESSION["error"] = "Username already taken!";
            header("Location: pages/register.php");
            exit();
        }

        // Insert the new user into the database
        $stmt = $conn->prepare(
            "INSERT INTO users (full_name, username, course, password)
             VALUES (:fullName, :username, :course, :password)"
        );

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(":fullName", $fullname);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":course", $course);
        $stmt->bindParam(":password", $hashedPassword);

        if ($stmt->execute()) {
            $_SESSION["success"] = "Registration successful! Please login.";
            header("Location: ../../login.php");
            exit();
        } else {
            $_SESSION["error"] = "An error occurred. Please try again.";
            header("Location: ../../register.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION["error"] = "Connection failed: " . $e->getMessage();
        header("Location: ../../register.php");
        exit();
    }
}
?>
