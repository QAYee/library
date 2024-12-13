<?php 
    if(!isset($_SESSION["username"]) || (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"]!="1")){
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #ff6b6b, #f5f7fa);
            color: #333;
            font-family: Arial, sans-serif;
        }
        .unauthorized-container {
            max-width: 600px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background: #ff6b6b;
            border: none;
        }
        .btn-primary:hover {
            background: #ff4757;
        }
    </style>
</head>
<body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="unauthorized-container text-center">
            <h1 class="display-1 text-danger fw-bold">401</h1>
            <h2 class="mb-4">Unauthorized Access</h2>
            <p class="lead mb-4">Sorry, you are not authorized to view this page. Please login with the correct credentials.</p>
            <a href="<?php echo BASE_URL; ?>login.php" class="btn btn-primary btn-lg">Go to Login</a>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php 
    exit;
    }
?>
