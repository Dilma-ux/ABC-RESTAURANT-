<?php
require 'db.php';
$db = Database::getInstance();
$conn = $db->getConnection();

// Check if an ID was provided in the URL
if (isset($_GET['id'])) {
    $gallery_id = $_GET['id'];

    // Fetch the gallery item from the database
    $query = "SELECT * FROM gallery WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $gallery_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $gallery_item = $result->fetch_assoc();
} else {
    // Redirect back to the admin dashboard if no ID is provided
    header("Location: admin_dashboard.php");
    exit();
}

// Handle form submission for updating the gallery item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'];
    $image_path = $_POST['image_path']; // You may need to handle file uploads here

    // Update the gallery item in the database
    $update_query = "UPDATE gallery SET name = ?, image_path = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('ssi', $name, $image_path, $gallery_id);
    
    if ($update_stmt->execute()) {
        // Redirect back to the admin dashboard after updating
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error updating gallery item.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Gallery Item</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .edit-section {
            background-color: #ffffff;
            border-radius: 10px;
            max-width: 600px;
            margin: 50px auto;
            margin-top: 10%;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .edit-section h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #ff5630;
            font-size: 1.8rem;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
            color: #555;
        }

        .input-group input[type="text"],
        .input-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            color: #333;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        .input-group input[type="text"]:focus,
        .input-group textarea:focus {
            border-color: #ff5630;
            outline: none;
            background-color: #fff;
        }

        .input-group textarea {
            resize: vertical;
            min-height: 150px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px 20px;
            background-color: #ff5630;
            color: #fff;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #e04a26;
        }
    </style>
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>

    <section class="edit-section">
        <h2>Edit Gallery Item</h2>
        <form action="edit_gallery_item.php?id=<?php echo $gallery_id; ?>" method="post">
            <div class="input-group">
                <label for="gallery_id">ID:</label>
                <input type="text" id="gallery_id" name="gallery_id" value="<?php echo htmlspecialchars($gallery_item['id']); ?>" disabled>
            </div>
            <div class="input-group">
                <label for="image_path">Image Path:</label>
                <input type="text" id="image_path" name="image_path" value="<?php echo htmlspecialchars($gallery_item['image_path']); ?>" required>
            </div>
            <div class="input-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($gallery_item['name']); ?>" required>
            </div>
            <button type="submit">Update Gallery Item</button>
        </form>
    </section>
</body>
</html>
