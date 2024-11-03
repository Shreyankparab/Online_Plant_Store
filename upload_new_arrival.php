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
    $new_arrival_name = $_POST['new_arrival_name'];
    $uploaded_at = date("Y-m-d H:i:s");

    // Handle Image 1 Upload
    $image_1 = $_FILES['new_arrival_image_1'];
    $image_1_name = uniqid() . "_" . basename($image_1['name']);
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/Project_Web_Dev_new/uploads/new_arrivals/";
    $target_file_1 = $target_dir . $image_1_name;

    // Handle Image 2 Upload
    $image_2 = $_FILES['new_arrival_image_2'];
    $image_2_name = uniqid() . "_" . basename($image_2['name']);
    $target_file_2 = $target_dir . $image_2_name;

    // Validate both images
    $image_info_1 = getimagesize($image_1['tmp_name']);
    $image_info_2 = getimagesize($image_2['tmp_name']);

    if ($image_info_1 === false || $image_info_2 === false) {
        echo "Error: One or both files are not valid images.";
    } elseif (($image_info_1[0] > 1500 || $image_info_1[1] > 1500) || ($image_info_2[0] > 1500 || $image_info_2[1] > 1500)) {
        echo "Error: Image dimensions should not exceed 1500x1500 pixels.";
    } else {
        // Proceed with file uploads if both images are valid
        if (move_uploaded_file($image_1['tmp_name'], $target_file_1) && move_uploaded_file($image_2['tmp_name'], $target_file_2)) {
            // Insert new arrival information into the database
            $stmt = $conn->prepare("INSERT INTO new_arrival (name, image_1, image_2, uploaded_at) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $new_arrival_name, $image_1_name, $image_2_name, $uploaded_at);
            
            if ($stmt->execute()) {
                echo "New Arrival images added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
            
            $stmt->close();
        } else {
            echo "Error uploading one or both images. Please try again.";
        }
    }
}

$conn->close();
?>
