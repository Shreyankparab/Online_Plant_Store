<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '1234', 'online_plant_store');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch 3 random plant records for the category section
$plantQuery = "SELECT * FROM plants ORDER BY RAND() LIMIT 3";
$plantResult = $conn->query($plantQuery);

// Fetch 4 random images for the book covers
$bookImagesQuery = "SELECT image, name, description, category, price FROM plants ORDER BY RAND() LIMIT 4"; // Updated query for random selection
$bookImagesResult = $conn->query($bookImagesQuery);

$bookImages = [];
if ($bookImagesResult && $bookImagesResult->num_rows > 0) {
    while ($row = $bookImagesResult->fetch_assoc()) {
        $bookImages[] = $row;
    }
}

// Close the database connection if no longer needed
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Plant Store</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<header class="header">
    <div class="header_logo">
        <img src="images/Logo_Main_1.png" alt="Online Plant Store Logo" style="width: 150px; height: auto;">
    </div>
    <div class="header_data">
        <div class="Home-Header" onclick="scrollToSection('home-section')">Home</div>  
        <div class="New-Arrivals-Header" onclick="scrollToSection('new-arrivals-section')">New Arrivals</div>    
        <div class="Contact-Us-Header" onclick="scrollToSection('contact-us-section')">Contact Us</div>    
        <div class="About-Us-Header" onclick="scrollToSection('about-us-section')">About Us</div>
        <div class="Cart-Header">
            <a href="mycart.php">
                <img src="images/cart-icon.png" alt="Cart Icon" style="width: 25px; height: auto;">
            </a>
        </div>

    </div>

    <div class="hamburger-icon" onclick="toggleMenu()">
        <div></div>
        <div></div>
        <div></div>
    </div>
</header>
    <!-- =========================================Javascript for Navigation================================================ -->
        <script>
            function scrollToSection(sectionId) {
                const section = document.getElementById(sectionId);
                if (section) {
                    section.scrollIntoView({ behavior: 'smooth' });
                }
            }
        </script>
    <!-- ================================================================================================================== -->

    <div id="home-section" class="container">
        <div id="carouselExampleIndicators" class="carousel slide slider" data-bs-ride="carousel" data-interval="1000">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 5"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="5" aria-label="Slide 6"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="images/Slider_1.webp" class="d-block w-100" alt="Slide 1">
                </div>
                <div class="carousel-item">
                    <img src="images/Slider_2.webp" class="d-block w-100" alt="Slide 2">
                </div>
                <div class="carousel-item">
                    <img src="images/Slider_3.webp" class="d-block w-100" alt="Slide 3">
                </div>
                <div class="carousel-item">
                    <img src="images/Slider_4.webp" class="d-block w-100" alt="Slide 4">
                </div>
                <div class="carousel-item">
                    <img src="images/Slider_5.webp" class="d-block w-100" alt="Slide 5">
                </div>
                <div class="carousel-item">
                    <img src="images/Slider_6_new.webp" class="d-block w-100" alt="Slide 5">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        
        <div class="container-category">
                

        <!-- <div class="category-title">
            <h3>Crad Title Goes Here</h3>

        </div> -->

        <div class="category-New-title">
            <h3 class="styled-heading">Card New Title Goes Here</h3>

        </div>


        <div class="category">
            <?php
            if ($plantResult->num_rows > 0) {
                while ($plant = $plantResult->fetch_assoc()) {
                    ?>
                    <div class="category-item">
                        <img src="uploads/<?php echo htmlspecialchars($plant['image']); ?>" alt="<?php echo htmlspecialchars($plant['name']); ?>" class="category-image">
                        <h4><?php echo htmlspecialchars($plant['name']); ?></h4>
                        <p class="card-description"><?php echo htmlspecialchars($plant['description']); ?></p>
                        <p class="card-price">₹<?php echo htmlspecialchars($plant['price']); ?></p>
                        <div class="button-container">
                            <button class="add-to-cart" onclick="addToCart('<?php echo $plant['id']; ?>', '<?php echo $plant['name']; ?>', '<?php echo $plant['image']; ?>', '<?php echo $plant['price']; ?>')">Add to Cart</button>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No plants available at the moment.</p>";
            }
            ?>
        </div>



        <script>
        function addToCart(id, name, image, price) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "add_to_cart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert("Item added to cart successfully!");
                } else {
                    alert("Failed to add item to cart.");
                }
            };
            const params = `id=${id}&name=${encodeURIComponent(name)}&image=${encodeURIComponent(image)}&price=${price}`;
            xhr.send(params);
        }
        </script>





        </div>
                <!-- ===================Retrive Images From Database====================== -->
                <?php
                    // Database connection
                    $conn = new mysqli("localhost", "root", "1234", "online_plant_store");
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Query to fetch the latest two images for new arrivals based on upload time
                    $sql = "SELECT image_1, image_2 FROM new_arrival ORDER BY uploaded_at DESC LIMIT 2";
                    $result = $conn->query($sql);

                    $images = [];
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $images[] = $row;
                        }
                    }
                    $conn->close();
                    ?>
                <!-- ===================================================================== -->


                <div class="temp">
                    <div class="temp-card-text">
                        <h3 class="styled-heading">Temp Card Text</h3>
                    </div>
                    <div class="temp-cards-container">
                        <?php if (isset($images[0]['image_1'])) : ?>
                            <div class="temp-cards" id="tempcards1">
                                <img src="uploads/new_arrivals/<?php echo $images[0]['image_1']; ?>" alt="Image 1" style="width:100%; height:auto;">
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($images[0]['image_2'])) : ?>
                            <div class="temp-cards" id="tempcards2">
                                <img src="uploads/new_arrivals/<?php echo $images[0]['image_2']; ?>" alt="Image 2" style="width:100%; height:auto;">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>




                <div id="new-arrivals-section" class="new_arrival_1">
            <div class="temp-card-text">
                <h3 class="styled-heading">Book Covers</h3>
            </div>
            <div class="book-container">
                <?php if (!empty($bookImages)): ?>
                    <?php foreach ($bookImages as $index => $plant): ?>
                        <div class="book">
                            <!-- Cover image -->
                            <div class="cover" style="background-image: url('uploads/<?php echo htmlspecialchars($plant['image']); ?>');"></div>
                            <!-- Book information -->
                            <div class="book-info">
                                <h4><?php echo htmlspecialchars($plant['name']); ?></h4>
                                <br>
                                <p><?php echo htmlspecialchars($plant['description']); ?></p>
                                <p>Category: <?php echo htmlspecialchars($plant['category']); ?></p>
                                <p>₹<?php echo htmlspecialchars($plant['price']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No books available at the moment.</p>
                <?php endif; ?>
            </div>
        </div>

        <div id="contact-us-section" class="contact_info">
            <div class="temp-card-text">
                    <h3 class="styled-heading">Contact Us</h3>
                </div>
            <div class="contact-form">
                <form action="contact_process.php" method="post">
                    <div class="contact-input-feilds">
                        <label for="name" class="form-label">Name : </label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="contact-input-feilds">
                        <label for="email" class="form-label">Email : </label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="contact-input-feilds">
                        <label for="phone" class="form-label">Phone Number : </label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="contact-input-feilds">
                        <label for="message" class="form-label">Message : </label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn-primary-contact">Submit</button>
                </form>
            </div>
        </div>

        <div id="about-us-section" class="footer">Footer</div>
    </div>
</body>
</html>
