<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== 'admin') {
    // Redirect to login page if not logged in as admin
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/adminstyle.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    
    <main>
    <!-- Existing Plant Upload Section -->
    <section>
        <h2>Welcome, Admin!</h2>
        <p>Here you can manage the Online Plant Store. Use the form below to add new plants to the store.</p>
    </section>
    
    <!-- Image Upload Form for Plants -->
    <section>
        <h3>Add a New Plant</h3>
        <form action="upload_plant.php" method="POST" enctype="multipart/form-data">
            <label for="plant_name">Plant Name:</label>
            <input type="text" id="plant_name" name="plant_name" required>

            <label for="plant_description">Plant Description:</label>
            <textarea id="plant_description" name="plant_description" rows="4" required></textarea>

            <label for="plant_category">Category:</label>
            <select id="plant_category" name="plant_category" required>
                <option value="Indoor">Indoor</option>
                <option value="Outdoor">Outdoor</option>
                <option value="Succulent">Succulent</option>
                <option value="Flowering">Flowering</option>
                <option value="Herbs">Herbs</option>
                <option value="Air Purifying">Air Purifying</option>
            </select>

            <label for="plant_price">Price (₹):</label>
            <input type="number" id="plant_price" name="plant_price" min="0" step="0.01" required>

            <label for="plant_image">Choose Image:</label>
            <input type="file" id="plant_image" name="plant_image" accept="image/*" required>

            <button type="submit">Upload Plant</button>
        </form>
    </section>
    
    <!-- New Section for New Arrival Image Upload Only -->
    <section>
        <h3>Add New Arrival Images</h3>
        <form action="upload_new_arrival.php" method="POST" enctype="multipart/form-data">
            <label for="new_arrival_name">Plant Name:</label>
            <input type="text" id="new_arrival_name" name="new_arrival_name" required>

            <label for="new_arrival_image_1">Choose Image 1:</label>
            <input type="file" id="new_arrival_image_1" name="new_arrival_image_1" accept="image/*" required>

            <label for="new_arrival_image_2">Choose Image 2:</label>
            <input type="file" id="new_arrival_image_2" name="new_arrival_image_2" accept="image/*" required>

            <button type="submit">Upload New Arrival Images</button>
        </form>
    </section>
</main>


    <footer>
        <p>&copy; 2024 Online Plant Store. Admin Panel.</p>
    </footer>
</body>
</html>
