<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Client Login | Network CRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="dist/css/auth.css" />
    <?php
    session_start(); // Start the session to access session variables
    // Include any necessary PHP files or configurations here
    // file to check if the user is already logged in
    require_once 'functions.php'; // Include your functions file if needed
    // Check if the user is already logged in
    if (isLoggedIn()) {
        // Redirect to the dashboard or home page if already logged in
        header('Location: dashboard.php');
        exit();
    }
    ?>

</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <h2>Client Login</h2>
            <form action="handle-login.php" method="POST" id="login-form">


                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="error-message" style="color: #f87171; margin-bottom: 1rem; text-align:center;">
                        <?php
                        // Display the error message if it exists
                        echo  $_SESSION['login_error'];
                        // Unset the error message after displaying it
                        unset($_SESSION['login_error']);
                        ?>
                    </div>
                <?php endif; ?>


                <div class="form-group">
                    <label for="username">Email Address</label>
                    <input type="text" id="username" name="username" required placeholder="john" />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password" />
                </div>
                <div class="form-group">
                    <label for="remember">
                        <input type="checkbox" id="remember" name="remember" />
                        Remember Me
                    </label>
                </div>
                <div class="form-group">
                    <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
                </div>
                <button type="submit" class="btn-login">Login</button>
            </form>

            <div class="footer">
                &copy; <?= date('Y') ?> Network CRM. All rights reserved.
            </div>
        </div>
    </div>

    <script src="dist/js/auth.js"></script>
</body>

</html>