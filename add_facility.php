<?php
session_start();
require 'db.php';
$db = Database::getInstance();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $icon = $_POST['icon'];

    $query = "INSERT INTO facilities (name, description, icon) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $name, $description, $icon);
    
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error adding facility.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Facility</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="add.css">
</head>
<body>
    <div class="container">
    <h2>Add New Facility</h2>
    <form action="add_facility.php" method="post">
        <label for="name">Facility Name:</label>
        <input type="text" name="name" required>
        <label for="description">Description:</label>
        <textarea name="description" required></textarea>
        <label for="icon">Icon:</label>
        <input type="text" name="icon" required>
        <button type="submit">Add Facility</button>
    </form>
    <a href="admin_dashboard.php" class="go-back-btn">Go Back</a>
    </div>
</body>
</html>