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
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .password-strength {
            font-size: 14px;
            margin-top: 5px;
            text-align: left;
        }

        .password-strength.weak {
            color: red;
        }

        .password-strength.strong {
            color: green;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; height: 100vh; font-family: Arial, sans-serif; background-color: #e2e2e2;">

    <!-- Header -->
    <header class="header">
        <h1>Library System</h1>
    </header>

    <!-- Navbar -->
    <?php require_once(ROOT_DIR."/includes/navbar.php");?>

    <!-- Main Content (Register Form) -->
    <div style="display: flex; justify-content: center; align-items: center; height: 70vh;">
        <div style="background-color:#FFFFFF; padding: 40px; border-radius: 10px; width: 100%; max-width: 400px; color: #333; box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1); text-align: center;">
            <h2 style="font-size: 24px; margin-bottom: 30px; color: #a33b3b;">Register</h2>

            <?php
            if (isset($_SESSION['error'])) {
                echo "<div class='error-message'>{$_SESSION['error']}</div>";
                unset($_SESSION['error']);
            }
            ?>

            <form action="app/auth/register_action.php" method="post" onsubmit="return validateForm()">
                <input type="text" name="full_name" placeholder="Full Name" required style="width: 100%; padding: 12px; margin: 10px 0; border: none; border-radius: 5px; font-size: 16px; background-color: #e2e2e2; color: #333;">
                <input type="text" name="username" placeholder="Username" required style="width: 100%; padding: 12px; margin: 10px 0; border: none; border-radius: 5px; font-size: 16px; background-color: #e2e2e2; color: #333;">
                <input type="text" name="course" placeholder="Course" required style="width: 100%; padding: 12px; margin: 10px 0; border: none; border-radius: 5px; font-size: 16px; background-color: #e2e2e2; color: #333;">
                <input type="password" id="password" name="password" placeholder="Create Password" required style="width: 100%; padding: 12px; margin: 10px 0; border: none; border-radius: 5px; font-size: 16px; background-color: #e2e2e2; color: #333;" onkeyup="checkPasswordStrength()">
                <div id="password-strength" class="password-strength"></div>
                <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm Password" required style="width: 100%; padding: 12px; margin: 10px 0; border: none; border-radius: 5px; font-size: 16px; background-color: #e2e2e2; color: #333;">
                <button type="submit" style="padding: 12px 20px; background-color: #a33b3b; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; width: 100%; margin-top: 10px;">Register</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <?php require_once(ROOT_DIR."/includes/footer.php")?>

    <script>
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthIndicator = document.getElementById('password-strength');
            
            if (password.length < 6) {
                strengthIndicator.textContent = "Password is too short (min. 6 characters)";
                strengthIndicator.className = 'password-strength weak';
            } else if (!/[A-Z]/.test(password) || !/[0-9]/.test(password)) {
                strengthIndicator.textContent = "Password is weak (add uppercase and numbers)";
                strengthIndicator.className = 'password-strength weak';
            } else {
                strengthIndicator.textContent = "Password is strong";
                strengthIndicator.className = 'password-strength strong';
            }
        }

        function validateForm() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
