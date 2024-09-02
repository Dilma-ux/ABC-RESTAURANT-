<?php
session_start();
require 'db.php';

$db = Database::getInstance();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $image_path = $_POST['image_path'];

    $query = "INSERT INTO gallery (name, image_path) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $name, $image_path);
    
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error adding gallery item.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Gallery Item</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="add.css">

</head>
<body>
    <div class="container">
    <h2>Add New Gallery Item</h2>
    <form action="add_gallery_item.php" method="post">
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <label for="image_path">Image Path:</label>
        <input type="text" name="image_path" required>
        <button type="submit">Add Gallery Item</button>
    </form>
    <a href="admin_dashboard.php" class="go-back-btn">Go Back</a>
    </div>
</body>
</html>
