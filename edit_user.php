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
    $result = $conn->query("SELECT * FROM users WHERE user_id = $id");
    $user = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $position = $_POST['position'];
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $user['password'];

        $query = "UPDATE users SET name = '$name', email = '$email', mobile = '$mobile', position = '$position', password = '$password' WHERE user_id = $id";
        $conn->query($query);
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
    <title>Edit User</title>
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
        .input-group input[type="email"],
        .input-group input[type="password"],
        .input-group select {
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
        .input-group input[type="email"]:focus,
        .input-group input[type="password"]:focus,
        .input-group select:focus {
            border-color: #ff5630;
            outline: none;
            background-color: #fff;
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
        <h2>Edit User</h2>
        <form action="edit_user.php?id=<?php echo $id; ?>" method="post">
            <div class="input-group">
                <label for="user_id">User ID:</label>
                <input type="text" id="user_id" name="user_id" value="<?php echo $user['user_id']; ?>" disabled>
            </div>
            <div class="input-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter new password if changing">
            </div>
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="input-group">
                <label for="mobile">Mobile:</label>
                <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($user['mobile']); ?>" required>
            </div>
            <div class="input-group">
                <label for="position">Position:</label>
                <select id="position" name="position" required>
                    <option value="staff" <?php if ($user['position'] == 'staff') echo 'selected'; ?>>Staff</option>
                    <option value="admin" <?php if ($user['position'] == 'admin') echo 'selected'; ?>>Admin</option>
                </select>
            </div>
            <button type="submit">Update User</button>
        </form>
    </section>
</body>
</html>
