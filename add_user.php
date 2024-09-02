<?php
session_start();
require 'db.php';
require 'MenuFactory.php'; // Include the MenuFactory classes

$db = Database::getInstance();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $special_offer = $_POST['special_offer'];

    // Choose the appropriate factory based on the category
    switch ($category) {
        case 'Starter':
            $factory = new StarterFactory();
            break;
        case 'Main Course':
            $factory = new MainCourseFactory();
            break;
        case 'Dessert':
            $factory = new DessertFactory();
            break;
        case 'Beverage':
            $factory = new BeverageFactory();
            break;
        case 'Special':
            $factory = new SpecialFactory();
            break;
        default:
            echo "Invalid category selected.";
            exit();
    }

    // Create the menu item using the factory
    $menuItem = $factory->createMenuItem($name, $description, $price, $special_offer);

    // Insert the menu item into the database
    $query = "INSERT INTO menu (name, description, price, category, special_offer) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssdss', $menuItem->name, $menuItem->description, $menuItem->price, $menuItem->category, $menuItem->specialOffer);
    
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error adding menu item.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Menu Item</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="add.css">

</head>
<body>
    <div class="container">
        <h2>Add New Menu Item</h2>
        <form action="add_menu.php" method="post">
            <label for="name">Menu Item Name:</label>
            <input type="text" name="name" required>
            <label for="description">Description:</label>
            <textarea name="description" required></textarea>
            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" required>
            <label for="category">Category:</label>
            <select name="category" required>
                <option value="Starter">Starter</option>
                <option value="Main Course">Main Course</option>
                <option value="Dessert">Dessert</option>
                <option value="Beverage">Beverage</option>
                <option value="Special">Special</option>
            </select>
            <label for="special_offer">Special Offer:</label>
            <input type="text" name="special_offer">
            <button type="submit">Add Menu Item</button>
        </form>
        <a href="admin_dashboard.php" class="go-back-btn">Go Back</a>
    </div>
</body>
</html>
