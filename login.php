<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '1234', 'online_plant_store');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check for Admin login
    if ($username === 'Admin@123' && $password === 'Admin@123') {
        $_SESSION['user_id'] = 'admin';
        header("Location: admin.php"); // Redirect to admin.php if credentials match
        exit();
    }

    // Check for regular user login
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            header("Location: index.php"); // Redirect to index.php on successful login
            exit();
        } else {
            echo "Invalid username or password!";
        }
    } else {
        echo "No user found with that username!";
    }

    $stmt->close();
}
$conn->close();
?>
