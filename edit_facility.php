<?php
session_start();
require 'db.php';
$db = Database::getInstance();
$conn = $db->getConnection();

if (!isset($_SESSION['position']) || strtolower($_SESSION['position']) !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM facilities WHERE facility_id = $id");
    $facility = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $icon = $_POST['icon'];
        $conn->query("UPDATE facilities SET name = '$name', description = '$description', icon = '$icon' WHERE facility_id = $id");
        header("Location: admin_dashboard.php");
    }
} else {
    header("Location: admin_dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Facility</title>
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

        /* Additional styling for icon preview */
        .icon-preview {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
            color: #555;
        }
    </style>
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>

    <section class="edit-section">
        <h2>Edit Facility</h2>
        <form action="edit_facility.php?id=<?php echo $id; ?>" method="post">
            <div class="input-group">
                <label for="icon">Icon:</label>
                <input type="text" id="icon" name="icon" value="<?php echo htmlspecialchars($facility['icon']); ?>" required>
                <div class="icon-preview">
                    <i class="<?php echo htmlspecialchars($facility['icon']); ?>" id="iconPreview"></i>
                </div>
            </div>
            <div class="input-group">
                <label for="name">Facility Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($facility['name']); ?>" required>
            </div>
            <div class="input-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($facility['description']); ?></textarea>
            </div>
            <button type="submit">Update Facility</button>
        </form>
    </section>

    <script>
        // JavaScript to update icon preview in real-time as the user types
        const iconInput = document.getElementById('icon');
        const iconPreview = document.getElementById('iconPreview');

        iconInput.addEventListener('input', function() {
            iconPreview.className = this.value;
        });
    </script>
</body>
</html>

