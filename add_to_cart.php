<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in";
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli('localhost', 'root', '1234', 'online_plant_store');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['id'];
    $name = $_POST['name'];
    $image = $_POST['image'];
    $price = $_POST['price'];

    // Check if the product is already in the user's cart
    $checkQuery = "SELECT * FROM user_cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the product is already in the cart, update the quantity
        $updateQuery = "UPDATE user_cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ii", $user_id, $product_id);
        $updateStmt->execute();
    } else {
        // If the product is not in the cart, insert it
        $insertQuery = "INSERT INTO user_cart (user_id, product_id, name, image, price, quantity) VALUES (?, ?, ?, ?, ?, 1)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("iisss", $user_id, $product_id, $name, $image, $price);
        $insertStmt->execute();
    }

    echo "success";
    exit();
}

// Close the database connection
$conn->close();
?>
