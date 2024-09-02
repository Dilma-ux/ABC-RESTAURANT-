<?php
session_start();
require 'db.php';
$db = Database::getInstance();
$conn = $db->getConnection();

$current_page = basename($_SERVER['PHP_SELF']);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
// Fetch menu items from the database for each category
$starters_result = $conn->query("SELECT name, description, price, image_path FROM menu WHERE category = 'Starter'");
$main_courses_result = $conn->query("SELECT name, description, price, image_path FROM menu WHERE category = 'Main Course'");
$desserts_result = $conn->query("SELECT name, description, price, image_path FROM menu WHERE category = 'Dessert'");
$beverages_result = $conn->query("SELECT name, description, price, image_path FROM menu WHERE category = 'Beverage'");
$specials_result = $conn->query("SELECT name, description, price, image_path, special_offer FROM menu WHERE category = 'Special'");
$user_id = $_SESSION['user_id'];

// Sample data for demonstration (replace with actual database queries)
$starters_result = [
    ['name' => 'Bruschetta', 'description' => 'Grilled bread with tomato, garlic, and basil', 'price' => '450', 'image' => 'bruschetta.jpg'],
    ['name' => 'Stuffed Mushrooms', 'description' => 'Mushrooms stuffed with cheese and herbs', 'price' => '600', 'image' => 'stuffed_mushrooms.jpg'],
    ['name' => 'Garlic Prawns', 'description' => 'Prawns sautéed in garlic butter sauce', 'price' => '850', 'image' => 'garlic_prawns.jpg'],
];

$main_courses_result = [
    ['name' => 'Grilled Chicken', 'description' => 'Tender grilled chicken served with vegetables', 'price' => '1200', 'image' => 'grilled_chicken.jpg'],
    ['name' => 'Beef Steak', 'description' => 'Juicy steak cooked to perfection', 'price' => '1800', 'image' => 'beef_steak.jpg'],
    ['name' => 'Vegetarian Lasagna', 'description' => 'Layers of pasta, vegetables, and cheese', 'price' => '950', 'image' => 'vegetarian_lasagna.jpg'],
];

$desserts_result = [
    ['name' => 'Chocolate Lava Cake', 'description' => 'Warm chocolate cake with a molten center', 'price' => '500', 'image' => 'chocolate_lava_cake.jpg'],
    ['name' => 'Cheesecake', 'description' => 'Creamy cheesecake with a graham cracker crust', 'price' => '550', 'image' => 'cheesecake.jpg'],
    ['name' => 'Tiramisu', 'description' => 'Classic Italian dessert with coffee and mascarpone', 'price' => '600', 'image' => 'tiramisu.jpeg'],
];

$beverages_result = [
    ['name' => 'Mojito', 'description' => 'Refreshing mint and lime cocktail', 'price' => '400', 'image' => 'mojito.jpg'],
    ['name' => 'Lemon Iced Tea', 'description' => 'Chilled iced tea with a hint of lemon', 'price' => '300', 'image' => 'lemon_iced_tea.jpg'],
    ['name' => 'Cappuccino', 'description' => 'Rich and frothy coffee drink', 'price' => '350', 'image' => 'cappuccino.jpg'],
];

$specials_result = [
    ['name' => 'Family Combo', 'description' => 'Includes a mix of starters, mains, and desserts.', 'price' => '3500', 'image' => 'family_combo.jpg', 'validity' => 'Valid until 10th of October 2024'],
    ['name' => 'Tiramisu Special', 'description' => 'Buy 3 Tiramisu and get 1 Lemon Iced Tea for free!', 'price' => '1800', 'image' => 'tiramisu_special.jpg', 'validity' => 'Limited time offer'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC Restaurant - Menu</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
    <style>
        /* Enhanced Cart Styling */
        .cart-section {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #ff8c00;
            padding-bottom: 10px;
        }

        .cart-header h3 {
            margin: 0;
            font-size: 1.8rem;
            color: #333;
        }

        .cart-header p {
            font-size: 1.2em;
            color: #007bff;
            margin: 0;
        }

        .cart-items {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .cart-items li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e5e5e5;
        }

        .cart-items li:last-child {
            border-bottom: none;
        }

        .cart-item-name {
            flex: 1;
            font-weight: 500;
            color: #555;
        }

        .cart-item-price {
            font-weight: 600;
            text-align: right;
            width: 100px;
            color: #333;
        }

        .remove-item-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            margin-left: 10px;
        }

        .remove-item-btn:hover {
            background-color: #c82333;
        }

        .cart-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 20px;
        }

        .cart-actions button {
            padding: 12px 25px;
            border: none;
            background-color: #ff8c00;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .cart-actions button:hover {
            background-color: #e07b00;
        }

        .cart-actions .cancel-btn {
            background-color: #f44336;
        }

        .cart-actions .cancel-btn:hover {
            background-color: #d32f2f;
        }
    </style>

    <script>
    let selectedItems = [];

    function addToCart(itemName, itemPrice) {
        let existingItem = selectedItems.find(item => item.name === itemName);

        if (existingItem) {
            existingItem.quantity += 1; // Increase the quantity if the item is already in the cart
        } else {
            selectedItems.push({ name: itemName, price: itemPrice, quantity: 1 });
        }

        // Check for special offer: 3 Tiramisu = 1 free Lemon Iced Tea
        applySpecialOffer();

        updateCart();
        scrollToCart(); // Scroll to the cart section after adding an item
    }

    function applySpecialOffer() {
        let tiramisu = selectedItems.find(item => item.name === 'Tiramisu');
        let lemonIcedTea = selectedItems.find(item => item.name === 'Lemon Iced Tea');

        if (tiramisu && tiramisu.quantity >= 3) {
            if (!lemonIcedTea || lemonIcedTea.specialOffer !== true) {
                // Add Lemon Iced Tea for free
                selectedItems.push({ name: 'Lemon Iced Tea', price: 0, quantity: 1, specialOffer: true });
                alert('Congratulations! You have received a free Lemon Iced Tea with your purchase of 3 Tiramisu.');
            }
        }
        
        // Ensure the free Lemon Iced Tea is removed if Tiramisu quantity drops below 3
        if (lemonIcedTea && tiramisu && tiramisu.quantity < 3) {
            selectedItems = selectedItems.filter(item => item.name !== 'Lemon Iced Tea' || item.specialOffer !== true);
        }
    }

    function removeFromCart(itemName) {
        let existingItem = selectedItems.find(item => item.name === itemName);

        if (existingItem) {
            if (existingItem.quantity > 1) {
                existingItem.quantity -= 1; // Decrease the quantity if more than one
            } else {
                selectedItems = selectedItems.filter(item => item.name !== itemName); // Remove the item completely if only one
            }
        }

        // Re-apply special offer logic in case items were removed
        applySpecialOffer();
        updateCart();
    }

    function updateCart() {
        let cartList = document.getElementById('cart-items');
        cartList.innerHTML = ''; // Clear the cart list
        let totalPrice = 0;

        selectedItems.forEach(item => {
            totalPrice += parseFloat(item.price) * item.quantity;
            let listItem = document.createElement('li');
            listItem.innerHTML = `
                <span class="cart-item-name">${item.name} * ${item.quantity}</span>
                <span class="cart-item-price">${item.price * item.quantity} LKR</span>
                <button class="remove-item-btn" onclick="removeFromCart('${item.name}')">Remove</button>
            `;
            cartList.appendChild(listItem);
        });

        document.getElementById('total-cart-price').innerText = `Total: ${totalPrice} LKR`;
        document.getElementById('cart-items-json').value = JSON.stringify(selectedItems);
        document.getElementById('total-price-hidden').value = totalPrice;
    }

    function scrollToCart() {
        document.querySelector('.cart-section').scrollIntoView({ behavior: 'smooth' });
    }

    function proceedToPayment() {
        let itemsJson = JSON.stringify(selectedItems);
        let totalPrice = document.getElementById('total-price-hidden').value;
        window.location.href = `payment.php?items=${encodeURIComponent(itemsJson)}&total=${totalPrice}`;
    }

    function resetCart() {
        selectedItems = [];
        updateCart();
    }
    </script>
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>

    <section class="menu">
        <h2>Our Menu</h2>

        <!-- Special Offers and Combos -->
        <?php if (count($specials_result) > 0): ?>
        <section class="special-offers">
            <h3>Special Offers & Combos</h3>
            <div class="menu-items">
                <?php foreach ($specials_result as $special): ?>
                    <div class="menu-item special" onclick="addToCart('<?php echo $special['name']; ?>', '<?php echo $special['price']; ?>')">
                        <img src="images/<?php echo $special['image']; ?>" alt="<?php echo $special['name']; ?>" class="menu-item-image">
                        <div class="menu-item-content">
                            <h4><?php echo $special['name']; ?></h4>
                            <p><?php echo $special['description']; ?></p>
                            <span class="price"><?php echo $special['price']; ?> LKR</span>
                            <span class="validity"><?php echo $special['validity']; ?></span>
                            <span class="special-badge">Special Offer!</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Starters -->
        <section class="menu-category">
            <h3>Starters</h3>
            <div class="menu-items">
                <?php foreach ($starters_result as $starter): ?>
                    <div class="menu-item" onclick="addToCart('<?php echo $starter['name']; ?>', '<?php echo $starter['price']; ?>')">
                        <img src="images/<?php echo $starter['image']; ?>" alt="<?php echo $starter['name']; ?>" class="menu-item-image">
                        <div class="menu-item-content">
                            <h4><?php echo $starter['name']; ?></h4>
                            <p><?php echo $starter['description']; ?></p>
                            <span class="price"><?php echo $starter['price']; ?> LKR</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Main Courses -->
        <section class="menu-category">
            <h3>Main Courses</h3>
            <div class="menu-items">
                <?php foreach ($main_courses_result as $main_course): ?>
                    <div class="menu-item" onclick="addToCart('<?php echo $main_course['name']; ?>', '<?php echo $main_course['price']; ?>')">
                        <img src="images/<?php echo $main_course['image']; ?>" alt="<?php echo $main_course['name']; ?>" class="menu-item-image">
                        <div class="menu-item-content">
                            <h4><?php echo $main_course['name']; ?></h4>
                            <p><?php echo $main_course['description']; ?></p>
                            <span class="price"><?php echo $main_course['price']; ?> LKR</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Desserts -->
        <section class="menu-category">
            <h3>Desserts</h3>
            <div class="menu-items">
                <?php foreach ($desserts_result as $dessert): ?>
                    <div class="menu-item" onclick="addToCart('<?php echo $dessert['name']; ?>', '<?php echo $dessert['price']; ?>')">
                        <img src="images/<?php echo $dessert['image']; ?>" alt="<?php echo $dessert['name']; ?>" class="menu-item-image">
                        <div class="menu-item-content">
                            <h4><?php echo $dessert['name']; ?></h4>
                            <p><?php echo $dessert['description']; ?></p>
                            <span class="price"><?php echo $dessert['price']; ?> LKR</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Beverages -->
        <section class="menu-category">
            <h3>Beverages</h3>
            <div class="menu-items">
                <?php foreach ($beverages_result as $beverage): ?>
                    <div class="menu-item" onclick="addToCart('<?php echo $beverage['name']; ?>', '<?php echo $beverage['price']; ?>')">
                        <img src="images/<?php echo $beverage['image']; ?>" alt="<?php echo $beverage['name']; ?>" class="menu-item-image">
                        <div class="menu-item-content">
                            <h4><?php echo $beverage['name']; ?></h4>
                            <p><?php echo $beverage['description']; ?></p>
                            <span class="price"><?php echo $beverage['price']; ?> LKR</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </section>

    <!-- Cart Section -->
    <section class="cart-section">
        <div class="cart-header">
            <h3>Your Cart</h3>
            <p id="total-cart-price">Total: 0 LKR</p>
        </div>
        <ul id="cart-items" class="cart-items"></ul>
        <form id="cart-form">
            <input type="hidden" id="cart-items-json" name="items">
            <input type="hidden" id="total-price-hidden" name="total_price">
            <div class="cart-actions">
                <button type="button" onclick="resetCart()" class="cancel-btn">Cancel</button>
                <button type="button" onclick="proceedToPayment()">Done</button>
            </div>
        </form>
    </section>

    <footer>
        <div class="footer-content">
            <p>&copy; 2024 ABC Restaurant. All rights reserved.</p>
            <div class="footer-links">
                <a href="privacy.php">Privacy Policy</a> | 
                <a href="terms.php">Terms of Service</a>
            </div>
        </div>
    </footer>
</body>
</html>
