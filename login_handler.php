<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '1234', 'online_plant_store');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loginInput = $_POST['username']; // This can be either username or email
    $password = $_POST['password'];

    // Check for Admin login
    if ($loginInput === 'Admin@123' && $password === 'Admin@123') {
        $_SESSION['user_id'] = 'admin';
        header("Location: admin.php");
        exit();
    }

    // Check for regular user login (using username or email)
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $loginInput, $loginInput);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            header("Location: index.php");
            exit();
        } else {
            // Redirect with error as URL parameter
            header("Location: login.php?error=Invalid username/email or password!");
            exit();
        }
    } else {
        // Redirect with error as URL parameter
        header("Location: login.php?error=No user found with that username or email!");
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
