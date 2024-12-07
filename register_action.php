<?php
include('includes/db.php');
session_start();

$full_name = $_POST['full_name'];
$username = $_POST['username'];
$course = $_POST['course'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Check if passwords match
if ($password !== $confirm_password) {
    $_SESSION['error'] = "Passwords do not match!";
    header('Location: pages/register.php');
    exit();
}

// Check if the username already exists
$result = $conn->query("SELECT * FROM users WHERE username='$username'");
if ($result->num_rows > 0) {
    $_SESSION['error'] = "Username already taken!";
    header('Location: pages/register.php');
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert the user into the database
$stmt = $conn->prepare("INSERT INTO users (full_name, username, course, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $full_name, $username, $course, $hashed_password);

if ($stmt->execute()) {
    $_SESSION['success'] = "Registration successful! Please login.";
    header('Location: pages/login.php');
} else {
    $_SESSION['error'] = "An error occurred. Please try again.";
    header('Location: pages/register.php');
}

$stmt->close();
$conn->close();
?>
