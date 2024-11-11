<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<p>Please log in to view your cart.</p>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli('localhost', 'root', '1234', 'online_plant_store');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch cart items for the specific user
$query = "SELECT * FROM user_cart WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Your cart is empty.</p>";
    exit();
}

// Initialize total amount
$totalAmount = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link rel="stylesheet" href="css/mycartstyle.css">
</head>
<body>


<div class="page-wrapper">
    <h2>My Cart</h2>
    <div class="cart-container">
        <?php while ($item = $result->fetch_assoc()): ?>
            <div class="cart-item" data-item-id="<?php echo $item['id']; ?>">
                <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                
                <div class="item-details">
                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                    <div class="price-quantity-total">
                        <p>Price: ₹<?php echo htmlspecialchars($item['price']); ?></p>
                        <p>
                            Quantity: 
                            <button class="quantity-btn decrease-quantity" data-item-id="<?php echo $item['id']; ?>">-</button>
                            <span class="quantity" id="quantity-<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['quantity']); ?></span>
                            <button class="quantity-btn increase-quantity" data-item-id="<?php echo $item['id']; ?>">+</button>
                        </p>
                        <p class="total-price" id="total-<?php echo $item['id']; ?>">Total: ₹<?php echo $item['price'] * $item['quantity']; ?></p>
                    </div>
                </div>

                <?php 
                // Accumulate total amount
                $totalAmount += $item['price'] * $item['quantity'];
                ?>
            </div>
        <?php endwhile; ?>
    </div>
    <div class="summary-box">
        <h3>Total Amount: ₹<span id="overall-total"><?php echo $totalAmount; ?></span></h3>
        <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
    </div>
</div>

<!-- ========================Script============================= -->
 <script>
document.querySelectorAll('.increase-quantity, .decrease-quantity').forEach(button => {
    button.addEventListener('click', function() {
        const itemId = this.getAttribute('data-item-id');
        const quantityElement = document.getElementById(`quantity-${itemId}`);
        const totalPriceElement = document.getElementById(`total-${itemId}`);
        let quantity = parseInt(quantityElement.textContent);
        const price = parseInt(totalPriceElement.textContent.replace(/[^0-9]/g, '')) / quantity;

        if (this.classList.contains('increase-quantity')) {
            quantity++;
        } else if (quantity > 1) { // Prevent quantity from going below 1
            quantity--;
        }

        // Update quantity display
        quantityElement.textContent = quantity;

        // Update total price for the item
        const newTotalPrice = price * quantity;
        totalPriceElement.textContent = `Total: ₹${newTotalPrice}`;

        // Update the overall total
        updateOverallTotal();
    });
});

function updateOverallTotal() {
    let overallTotal = 0;
    document.querySelectorAll('.total-price').forEach(item => {
        overallTotal += parseInt(item.textContent.replace(/[^0-9]/g, ''));
    });
    document.getElementById('overall-total').textContent = overallTotal;
}
</script>

<!-- ===================================================== -->

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
