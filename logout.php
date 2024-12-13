<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
require_once("includes/header.php");


    session_destroy();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid vh-100 d-flex justify-content-center align-items-center bg-light">
    <div class="card shadow-lg border-0" style="max-width: 24rem; border-radius: 15px;">
        <div class="card-body text-center">
            <h5 class="card-title text-primary fw-bold mb-3">You have been logged out</h5>
            <p class="card-text text-muted mb-4">Thank you for visiting. You are now logged out. Have a great day!</p>
            <a href="/login.php" class="btn btn-primary btn-lg px-4 py-2 shadow">Go to Login</a>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
