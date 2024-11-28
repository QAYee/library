<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Center the form on the page */
        .login-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
require_once("includes\header.php");
if(!isset($_SESSION["wrong"])){
    $messErr = $_SESSION["wrong"];
    echo $messErr;
    unset($_SESSION["wrong"]);
}

?>
    <!-- Navbar -->
    <?php
require_once("includes\\navbar.php");
?>
    <!-- Admin Login Section -->
    <div class="login-container bg-light">
        <div class="card login-card">
            <div class="card-body">
                <h3 class="text-center mb-4">Admin Login</h3>
                <!-- Login Form -->
                <form action="app/auth/Login.php" method="POST">
                <?php 
                            if(isset($messErr)){
                            ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Holy guacamole!</strong> lagi kanalang mali.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                                <?php 
                                }
                                ?>
                    <div class="mb-3">
                        <label for="adminUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="adminUsername" placeholder="Enter your username" required>
                    </div>
                    <div class="mb-3">
                        <label for="adminPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="adminPassword" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>

    <?php require_once("includes\\footer.php");?>
