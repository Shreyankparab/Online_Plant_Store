<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Plant Store - Login</title>
    <link rel="stylesheet" href="css/reglogstyle.css">
    <script>
        // Check if there's an error parameter in the URL
        <?php
        if (isset($_GET['error'])) {
            $error_message = htmlspecialchars($_GET['error']);
            echo "alert('$error_message');";  // Trigger JavaScript alert with the error message
        }
        ?>
    </script>
</head>
<body>
    <div class="background-image"></div> <!-- Background image -->

    <div class="form-container">
        <h1>Login</h1>

        <form action="login_handler.php" method="POST">
            <input type="text" name="username" placeholder="Enter your username or email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <div class="button-container">
                <button type="submit">Submit</button>
            </div>
        </form>
        
        <div class="footer">
            <p>Don't have an account? <a href="registration.php">Register here</a></p>
        </div>
    </div>

</body>
</html>
