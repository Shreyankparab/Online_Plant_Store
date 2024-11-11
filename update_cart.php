<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '1234', 'online_plant_store');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $item_id = intval($_POST['item_id']);
    $current_quantity = intval($_POST['current_quantity']);
    $action = $_POST['action'];

    // Adjust quantity based on action
    if ($action === 'increase') {
        $new_quantity = $current_quantity + 1;
    } elseif ($action === 'decrease' && $current_quantity > 1) {
        $new_quantity = $current_quantity - 1;
    } else {
        $new_quantity = $current_quantity; // If decreasing below 1, keep at 1
    }

    // Update the new quantity in the database
    $query = "UPDATE user_cart SET quantity = ? WHERE user_id = ? AND id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $new_quantity, $user_id, $item_id);
    $stmt->execute();

    // Redirect back to the cart page
    header("Location: cart.php");
    exit();
}

// Close the database connection
$conn->close();
?>
