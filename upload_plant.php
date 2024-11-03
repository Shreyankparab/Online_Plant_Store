
<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '1234', 'online_plant_store');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plant_name = $_POST['plant_name'];
    $plant_description = $_POST['plant_description'];
    $plant_category = $_POST['plant_category'];
    $plant_price = $_POST['plant_price'];  // Capture the price input from the form

    // Handle Image Upload
    $image = $_FILES['plant_image'];
    $image_name = uniqid() . "_" . basename($image['name']);
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/Project_Web_Dev_new/uploads/";
    $target_file = $target_dir . $image_name;

    // Check if the file is an actual image
    $image_info = getimagesize($image['tmp_name']);
    if ($image_info === false) {
        echo "Error: File is not a valid image.";
    } else {
        list($width, $height) = $image_info;

        // Check if image dimensions exceed 400x400 pixels
        if ($width > 1500 || $height > 1500) {
            echo "Error: Image dimensions should not exceed 400x400 pixels.";
        } else {
            // Proceed with file upload if dimensions are within limits
            if (move_uploaded_file($image['tmp_name'], $target_file)) {
                // Insert plant information, including price, into the database
                $stmt = $conn->prepare("INSERT INTO plants (name, description, category, price, image) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssis", $plant_name, $plant_description, $plant_category, $plant_price, $image_name);
                
                if ($stmt->execute()) {
                    echo "Plant added successfully!";
                } else {
                    echo "Error: " . $stmt->error;
                }
                
                $stmt->close();
            } else {
                echo "Error uploading the image. Please try again.";
            }
        }
    }
}

$conn->close();
?>